<?php
// src/Controller/CommandeController.php
namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function index(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em
    ): Response {
        // On prépare l'entité et le formulaire
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);

        //  On traite la requête
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Hash du mot de passe issu du champ plainPassword
            $hashed = $passwordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );
            $user->setPassword($hashed);
            $user->setRoles(['ROLE_USER']);
            //  On persiste et flush en base
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Inscription réussie ! Vous pouvez maintenant vous connecter.');


            // Redirection vers la page de login
            return $this->redirectToRoute('app_login');
        }

        //  On renvoie le formulaire à Twig sous la clé registrationForm
        return $this->render('inscription/index.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
