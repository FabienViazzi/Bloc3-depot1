<?php
// src/Controller/CommandeController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si l'utilisateur est déjà connecté, on le redirige vers l'accueil
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        // Erreur d'authentification (le cas échéant)
        $error = $authenticationUtils->getLastAuthenticationError();
        // Dernier email saisi
        $lastEmail = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            
            'last_username' => $authenticationUtils->getLastUsername(),
            'error'      => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Le firewall interceptera cette route : le code ne sera jamais exécuté
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
