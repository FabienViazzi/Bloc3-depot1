<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientProfileController extends AbstractController
{
    #[Route('/client/profile', name: 'app_client_profile')]
    public function index(): Response
    {
        // On récupère l'utilisateur connecté
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login'); // Redirige vers la page de connexion
        }

        return $this->render('client_profile/index.html.twig', [
            'user' => $user,
        ]);
    }
}
