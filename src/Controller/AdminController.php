<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Form\OfferType;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/offres', name: 'admin_offers')]
    public function index(OfferRepository $offerRepo): Response
    {
        $offers = $offerRepo->findAll();

        return $this->render('admin_offers/index.html.twig', [
            'offers' => $offers,
        ]);
    }

    #[Route('/offres/ajouter', name: 'admin_offer_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $offer = new Offer();
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($offer);
            $em->flush();

            return $this->redirectToRoute('admin_offers');
        }

        return $this->render('admin_offers/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/offres/supprimer/{id}', name: 'admin_offer_delete')]
    public function delete(Offer $offer, EntityManagerInterface $em): Response
    {
        $em->remove($offer);
        $em->flush();

        return $this->redirectToRoute('admin_offers');
    }
    #[Route('/offres/modifier/{id}', name: 'admin_offer_edit')]
    public function edit(Offer $offer, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('admin_offers');
        }

        return $this->render('admin_offers/edit.html.twig', [
            'form' => $form->createView(),
            'offer' => $offer,
        ]);
    }
}
