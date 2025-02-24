<?php

namespace App\Controller;

// Importation des classes nécessaires depuis le framework Symfony et d'autres namespaces
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Ad;
use App\Form\AdType;

final class CreateAdController extends AbstractController
{

    #[Route('/ads/create', name: 'ad_create')]
    public function create(Request $request, EntityManagerInterface $em, Security $security): Response
    {
        // Création d'une nouvelle instance de l'entité Ad
        $ad = new Ad();
        
        // Création du formulaire pour l'entité Ad
        $form = $this->createForm(AdType::class, $ad);
        
        // Traitement de la requête et remplissage du formulaire avec les données soumises
        $form->handleRequest($request);
        
        // Récupération de l'utilisateur connecté
        $user = $security->getUser();

        // Si l'utilisateur est connecté, l'associer à l'annonce
        if ($user) {
            $ad->setUser($user);
        }

        // Si le formulaire est soumis et valide, persister l'annonce en base de données
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($ad);
            $em->flush();

            // Rediriger vers la page d'accueil après la création de l'annonce
            return $this->redirectToRoute('app_index');
        }

        // Rendre le template 'ad/create.html.twig' avec le formulaire de création d'annonce
        return $this->render('ad/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
