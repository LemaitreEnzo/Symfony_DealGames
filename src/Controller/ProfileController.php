<?php

namespace App\Controller;

use App\Form\ProfileEditFormType;
use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\AdRepository;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'profile_show')]
    public function show(AdRepository $adRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }
        $ads = $adRepository->findBy(['user' => $user]);

        return $this->render('profile/show.html.twig', [
            'user' => $user,
            'ads' => $ads,
        ]);
    }

    #[Route('/profile/edit', name: 'profile_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, UserInterface $user): Response
    {
        $form = $this->createForm(ProfileEditFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Profile updated successfully.');
            return $this->redirectToRoute('profile_show');
        }

        return $this->render('profile/edit.html.twig', [
            'editForm' => $form->createView(),
        ]);
    }

    #[Route('/reset-password', name: 'profile_change_password', methods: ['GET', 'POST'])]
    public function changePassword(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user instanceof \Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface) {
            throw new \LogicException('The user must implement PasswordAuthenticatedUserInterface.');
        }

        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('plainPassword')->getData();

            /** @var User $user */
            $user->setPassword(password_hash($newPassword, PASSWORD_BCRYPT));

            $entityManager->flush();
            $this->addFlash('success', 'Mot de passe changé avec succès.');
            return $this->redirectToRoute('profile_show');
        }

        return $this->render('profile/change_password.html.twig', [
            'changePasswordForm' => $form->createView(),
        ]);
    }
}
