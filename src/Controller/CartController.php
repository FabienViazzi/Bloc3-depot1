<?php
// src/Controller/CartController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\OfferRepository;

#[Route('/api/cart', name: 'api_cart_')]
class CartController extends AbstractController
{
    public function __construct(
        private RequestStack    $requestStack,
        private OfferRepository $offerRepo
    ) {}

    // GET  /api/cart
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $session    = $this->requestStack->getSession();
        $cart       = $session->get('cart', []);
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

        return $this->json([
            'items'      => $items,
            'totalItems' => $totalItems,
            'totalPrice' => $totalPrice,
        ]);
    }


    #[Route('/add/{id}', name: 'add', methods: ['POST'])]
    public function add(int $id): JsonResponse
    {
        $session         = $this->requestStack->getSession();
        $cart            = $session->get('cart', []);
        $cart[$id]       = ($cart[$id] ?? 0) + 1;
        $session->set('cart', $cart);

        // on renvoie la même réponse que list()
        return $this->forward(self::class . '::list');
    }

    // POST /api/cart/remove/{id}
    #[Route('/remove/{id}', name: 'remove', methods: ['POST'])]
    public function remove(int $id): JsonResponse
    {
        $session = $this->requestStack->getSession();
        $cart    = $session->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            $session->set('cart', $cart);
        }

        return $this->forward(self::class . '::list');
    }
}
