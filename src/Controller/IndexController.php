<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AdRepository;

final class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(AdRepository $adRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }


        $ads = $adRepository->findAll();

        return $this->render('index/index.html.twig', [
            'ads' => $ads,
        ]);
    }

    #[Route('/ads/{id}', name: 'ad_show', methods: ['GET'])]
    public function show(int $id, AdRepository $adRepository): Response
    {
        $ad = $adRepository->find($id);
    
        if (!$ad) {
            throw $this->createNotFoundException('L\'annonce n\'existe pas.');
        }
    
        return $this->render('ad/show.html.twig', [
            'ad' => $ad,
        ]);
    }
    
    public function findByCategory(AdRepository $adRepository): Response
    {
        $ads = $adRepository->findAllSortedByCategory();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads,
        ]);
    }
}
