<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OffersController extends AbstractController
{
    #[Route('/offers', name: 'offers_list')]
    public function index(): Response
    {
        // 1) Définis un tableau d'exemple d'offres
        $offers = [
            ['name' => 'Solo',       'places' => 1, 'price' => 50],
            ['name' => 'Duo',        'places' => 2, 'price' => 90],
            ['name' => 'Familiale',  'places' => 4, 'price' => 160],
        ];

        // 2) Passe-le au template sous la clé 'offers'
        return $this->render('offers/index.html.twig', [
            'offers' => $offers,
        ]);
    }
}
