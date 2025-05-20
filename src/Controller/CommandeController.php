<?php
// src/Controller/CommandeController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\OfferRepository;

class CommandeController extends AbstractController
{
    public function __construct(
        private RequestStack    $requestStack,
        private OfferRepository $offerRepo
    ) {}

    #[Route('/commande', name: 'commande')]
    public function index(): Response
    {
        $session = $this->requestStack->getSession();
        $cart    = $session->get('cart', []);

        $items      = [];
        $totalItems = 0;
        $totalPrice = 0.0;

        foreach ($cart as $offerId => $qty) {
            if ($offer = $this->offerRepo->find($offerId)) {
                $price    = $offer->getPrice();
                $subtotal = $price * $qty;

                $items[] = [
                    'offer'    => [
                        'id'    => $offer->getId(),
                        'name'  => $offer->getName(),
                        'price' => $price,
                    ],
                    'quantity' => $qty,
                    'subtotal' => $subtotal,
                ];

                $totalItems += $qty;
                $totalPrice += $subtotal;
            }
        }

        return $this->render('commande/index.html.twig', [
            'items'      => $items,
            'totalItems' => $totalItems,
            'totalPrice' => $totalPrice,
        ]);
    }
}
