<?php 

// src/Controller/OffersController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\OfferRepository;

class OffersController extends AbstractController
{
    #[Route('/offres', name: 'offers_list')]
    public function index(OfferRepository $offerRepo): Response
    {
        $offers = $offerRepo->findAll();

        return $this->render('offers/index.html.twig', [
            'offers' => $offers,
        ]);
    }
}
