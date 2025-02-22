<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Ad;

final class DeleteAdController extends AbstractController
{

    #[Route('/ads/{id}/delete', name: 'ad_delete', methods: ['POST'])]
    public function delete(Request $request, Ad $ad, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ad->getId(), $request->request->get('_token'))) {
            $em->remove($ad);
            $em->flush();
        }

        return $this->redirectToRoute('app_index');
    }
}
