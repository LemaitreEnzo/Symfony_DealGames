<?php

namespace App\Controller;

// Importation des classes nécessaires depuis le framework Symfony et d'autres namespaces
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AdRepository;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategoryRepository;

final class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(AdRepository $adRepository, CategoryRepository $categoryRepository, Request $request): Response
    {
        // Récupération du paramètre de requête 'category' depuis l'objet Request
        $categoryId = $request->query->get('category');
        
        // Récupération de toutes les catégories depuis le repository des catégories
        $categories = $categoryRepository->findAll();

        // Si un ID de catégorie est fourni, récupérer les annonces correspondant à cette catégorie, sinon récupérer toutes les annonces
        if ($categoryId) {
            $ads = $adRepository->findBy(['category' => $categoryId]);
        } else {
            $ads = $adRepository->findAll();
        }

        // Rendre le template 'index/index.html.twig' avec les annonces et les catégories récupérées
        return $this->render('index/index.html.twig', [
            'ads' => $ads,
            'categories' => $categories,
        ]);
    }
    
    #[Route('/ads/{id}', name: 'ad_show', methods: ['GET'])]
    public function show(int $id, AdRepository $adRepository): Response
    {
        // Récupération de l'annonce avec l'ID donné depuis le repository des annonces
        $ad = $adRepository->find($id);

        // Si l'annonce n'existe pas, lancer une exception 404
        if (!$ad) {
            throw $this->createNotFoundException('L\'annonce n\'existe pas.');
        }

        // Rendre le template 'ad/show.html.twig' avec l'annonce récupérée
        return $this->render('ad/show.html.twig', [
            'ad' => $ad,
        ]);
    }
}
