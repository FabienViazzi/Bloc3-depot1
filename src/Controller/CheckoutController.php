<?php
// src/Controller/CheckoutController.php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\EBillet;
use App\Repository\OfferRepository;
use App\Service\MockPaymentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CheckoutController extends AbstractController
{
    public function __construct(
        private OfferRepository        $offerRepo,
        private EntityManagerInterface $em,
        private MockPaymentService     $paymentService,
        private MailerInterface        $mailer
    ) {}

    #[Route(path: '/checkout', name: 'checkout', methods: ['GET'])]
    public function show(SessionInterface $session): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $cart  = $session->get('cart', []);
        $items = [];
        $total = 0.0;

        foreach ($cart as $id => $qty) {
            if ($offer = $this->offerRepo->find($id)) {
                $price    = (float) $offer->getPrice();
                $subtotal = $price * $qty;
                $total   += $subtotal;
                $items[]  = [
                    'offer'    => $offer,
                    'quantity' => $qty,
                    'subtotal' => $subtotal,
                ];
            }
        }

        return $this->render('paiement/index.html.twig', [
            'items' => $items,
            'total' => $total,
        ]);
    }

    #[Route(path: '/checkout/process', name: 'checkout_process', methods: ['POST'])]
    public function process(SessionInterface $session): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Vérification du panier
        $cart = $session->get('cart', []);
        if (empty($cart)) {
            $this->addFlash('warning', 'Votre panier est vide.');
            return $this->redirectToRoute('checkout');
        }

        // Calcul du total
        $total = 0.0;
        foreach ($cart as $id => $qty) {
            if ($offer = $this->offerRepo->find($id)) {
                $total += (float) $offer->getPrice() * $qty;
            }
        }

        // Mock paiement
        if (! $this->paymentService->charge($total)) {
            $this->addFlash('danger', 'Le paiement a échoué, veuillez réessayer.');
            return $this->redirectToRoute('checkout');
        }

        // Création de la commande
        $order = new Order();
        $order
            ->setCreatedAt(new \DateTimeImmutable())
            ->setClefAchat(bin2hex(random_bytes(16)))
            ->setFkUser($this->getUser());
        foreach ($cart as $id => $qty) {
            if ($offer = $this->offerRepo->find($id)) {
                for ($i = 0; $i < $qty; $i++) {
                    $order->addOffer($offer);
                }
            }
        }
        $this->em->persist($order);
        $this->em->flush();

        // Génération de la clé finale
        /** @var \App\Entity\User $user */
        $user     = $this->getUser();
        $finalKey = hash('sha256', $user->getUserKey() . $order->getClefAchat());

        // --- Fallback Google Charts pour le QR Code ---
        $qrUri = sprintf(
            'https://quickchart.io/qr?text="&ENCODEURL(',urlencode($finalKey),')'
        );
        // -----------------------------------------------

        // Création et persistance de l’e-billet
        $eBillet = (new EBillet())
            ->setOrder($order)
            ->setClefFinale($finalKey)
            ->setQrCode($qrUri);
        $this->em->persist($eBillet);
        $this->em->flush();

        // Envoi de l’e-mail de confirmation
        $email = (new TemplatedEmail())
            ->from('favidevweb@gmail.com')
            ->to($user->getEmail())
            ->subject('Confirmation de votre billet JO 2024')
            ->htmlTemplate('emails/confirmation.html.twig')
            ->context([
                'user'    => $user,
                'order'   => $order,
                'eBillet' => $eBillet,
                'clefFinale' => $finalKey,   // Chaîne (clé du billet)
                'isMail' => true,
            ]);
        $this->mailer->send($email);

        // On vide le panier
        $session->remove('cart');

        return $this->render('paiement/confirmation.html.twig', [
            'eBillet' => $eBillet,
        ]);
    }
}
