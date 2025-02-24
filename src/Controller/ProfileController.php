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

/**
 * Contrôleur pour gérer le profil utilisateur.
 */
class ProfileController extends AbstractController
{
    /**
     * Affiche le profil de l'utilisateur connecté.
     *
     * @param AdRepository $adRepository Repository des annonces
     * @return Response
     */
    #[Route('/profile', name: 'profile_show')]
    public function show(AdRepository $adRepository): Response
    {
        // Récupération de l'utilisateur connecté
        $user = $this->getUser();

        // Vérification si l'utilisateur est bien connecté
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }
        
        // Récupération des annonces de l'utilisateur
        $ads = $adRepository->findBy(['user' => $user]);

        // Affichage du profil et des annonces associées
        return $this->render('profile/show.html.twig', [
            'user' => $user,
            'ads' => $ads,
        ]);
    }

    /**
     * Permet à l'utilisateur de modifier son profil.
     *
     * @param Request $request Requête HTTP
     * @param EntityManagerInterface $entityManager Gestionnaire d'entités
     * @param UserInterface $user Utilisateur connecté
     * @return Response
     */
    #[Route('/profile/edit', name: 'profile_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, UserInterface $user): Response
    {
        // Création du formulaire d'édition du profil
        $form = $this->createForm(ProfileEditFormType::class, $user);
        $form->handleRequest($request);


        // Traitement du formulaire après soumission et validation
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush(); // Enregistrement des modifications
            $this->addFlash('success', 'Profile updated successfully.');
            return $this->redirectToRoute('profile_show');
        }

        // Affichage du formulaire d'édition
        return $this->render('profile/edit.html.twig', [
            'editForm' => $form->createView(),
        ]);
    }

    /**
     * Permet à l'utilisateur de changer son mot de passe.
     *
     * @param Request $request Requête HTTP
     * @param UserPasswordHasherInterface $passwordHasher Service de hachage de mot de passe
     * @param EntityManagerInterface $entityManager Gestionnaire d'entités
     * @return Response
     */
    #[Route('/reset-password', name: 'profile_change_password', methods: ['GET', 'POST'])]
    public function changePassword(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        // Récupération de l'utilisateur connecté
        $user = $this->getUser();

        // Vérification si l'utilisateur implémente bien l'interface requise
        if (!$user instanceof \Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface) {
            throw new \LogicException('The user must implement PasswordAuthenticatedUserInterface.');
        }

        // Création du formulaire de changement de mot de passe
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        // Traitement du formulaire après soumission et validation
        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('plainPassword')->getData(); // Récupération du nouveau mot de passe

            /** @var User $user */
            $user->setPassword(password_hash($newPassword, PASSWORD_BCRYPT)); // Hachage et mise à jour du mot de passe

            $entityManager->flush(); // Sauvegarde des modifications
            $this->addFlash('success', 'Mot de passe changé avec succès.');
            return $this->redirectToRoute('profile_show');
        }

        // Affichage du formulaire de changement de mot de passe
        return $this->render('profile/change_password.html.twig', [
            'changePasswordForm' => $form->createView(),
        ]);
    }
}
