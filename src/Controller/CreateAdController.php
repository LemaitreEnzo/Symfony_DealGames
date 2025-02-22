<?php

namespace App\Controller;

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
        $ad = new Ad();
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);
        $user = $security->getUser();

        if ($user) {
            $ad->setUser($user);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($ad);
            $em->flush();

            return $this->redirectToRoute('app_index');
        }

        return $this->render('ad/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
