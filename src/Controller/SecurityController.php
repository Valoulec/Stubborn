<?php

namespace App\Controller;

use App\Form\OubliPassFromType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    private $entityManager;
    private $passwordHasher;

// Injection des dépendances via le constructeur
public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
{
    $this->entityManager = $entityManager;
    $this->passwordHasher = $passwordHasher;
}


    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si l'utilisateur est déjà connecté, rediriger
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/reset-password', name: 'app_reset_password')]
    public function resetPassword(Request $request, UserRepository $userRepository): Response
    {
        $form = $this->createForm(OubliPassFromType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $email = $data['email'];
            $oldPassword = $data['oldPassword'];
            $newPassword = $data['newPassword']; // Premier champ du mot de passe répété

            // Vérifier si l'email existe
            $user = $userRepository->findOneBy(['email' => $email]);
            if (!$user) {
                $this->addFlash('danger', 'Aucun utilisateur trouvé avec cet email.');
                return $this->redirectToRoute('app_reset_password');
            }

            // Vérifier l'ancien mot de passe
            if (!$this->passwordHasher->isPasswordValid($user, $oldPassword)) {
                $this->addFlash('danger', 'L\'ancien mot de passe est incorrect.');
                return $this->redirectToRoute('app_reset_password');
            }

            // Mettre à jour le mot de passe
            $user->setPassword($this->passwordHasher->hashPassword($user, $newPassword));
            $this->entityManager->flush();

            // Rediriger vers la page de connexion après la mise à jour
            $this->addFlash('success', 'Votre mot de passe a été mis à jour.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password.html.twig', [
            'resetPasswordForm' => $form->createView(),
        ]);
    }
}  
