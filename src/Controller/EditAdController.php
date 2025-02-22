<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Ad;
use App\Form\AdType;

final class EditAdController extends AbstractController
{
    #[Route('/ads/{id}/edit', name: 'ad_edit')]
    public function edit(Request $request, Ad $ad, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('ad_show', ['id' => $ad->getId()]);
        }

        return $this->render('ad/edit.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad,
        ]);
    }
}
