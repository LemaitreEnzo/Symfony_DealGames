<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use App\Security\AuthAuthenticator;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Contrôleur pour gérer l'enregistrement des utilisateurs.
 */
class RegistrationController extends AbstractController
{
    /**
     * Constructeur pour injecter le vérificateur d'email.
     *
     * @param EmailVerifier $emailVerifier Vérificateur d'email
     */
    public function __construct(private EmailVerifier $emailVerifier) {}

    /**
     * Permet à un utilisateur de s'enregistrer.
     *
     * @param Request $request Requête HTTP
     * @param UserPasswordHasherInterface $userPasswordHasher Service pour hacher les mots de passe
     * @param EntityManagerInterface $entityManager Gestionnaire d'entités
     * @param Security $security Service de sécurité
     * @return Response
     */
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        Security $security
    ): Response {
        // Création d'un nouvel utilisateur
        $user = new User();
        // Création du formulaire d'enregistrement
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        // Traitement du formulaire après soumission
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // Hachage du mot de passe et attribution des rôles
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            $user->setRoles(['ROLE_USER']);

            // Enregistrement de l'utilisateur dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Envoi de l'email de confirmation d'inscription
            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('mailer@example.com', 'Enzo')) // Email de l'expéditeur
                    ->to((string) $user->getEmail()) // Email du destinataire
                    ->subject('Please Confirm your Email') // Sujet de l'email
                    ->htmlTemplate('registration/confirmation_email.html.twig') // Template de l'email
            );

            // Connexion automatique de l'utilisateur après enregistrement
            return $security->login($user, AuthAuthenticator::class, 'main');
        }

        // Affichage du formulaire d'enregistrement
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    /**
     * Permet de vérifier l'email de l'utilisateur.
     *
     * @param Request $request Requête HTTP
     * @param TranslatorInterface $translator Service de traduction
     * @return Response
     */
    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        // Vérification de l'accès de l'utilisateur (doit être authentifié)
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        try {
            /** @var User $user */
            $user = $this->getUser();
            // Traitement de la confirmation de l'email
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            // Gestion des erreurs liées à la vérification de l'email
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));
            return $this->redirectToRoute('app_register');
        }

        // Message de succès après la vérification de l'email
        $this->addFlash('success', 'Your email address has been verified.');

        // Redirection vers la page d'accueil
        return $this->redirectToRoute('app_index');
    }
}
