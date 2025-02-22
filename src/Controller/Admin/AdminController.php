<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Ad;
use App\Form\AdType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
     #[Route('/admin', name: 'admin_dashboard')]
    public function dashboard(EntityManagerInterface $entityManager)
    {
        $users = $entityManager->getRepository(User::class)->findAll();
        $ads = $entityManager->getRepository(Ad::class)->findAll();

        return $this->render('admin/dashboard.html.twig', [
            'users' => $users,
            'ads' => $ads,
        ]);
    }

    #[Route('/admin/user/{id}/edit', name: 'admin_user_edit')]
    public function editUser(User $user, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $password = $form->get('password')->getData();
            $user->setPassword(password_hash($password, PASSWORD_BCRYPT));

            $entityManager->flush();

            $this->addFlash('success', 'L\'utilisateur a été modifié avec succès.');

            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/edit_user.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/admin/ad/{id}/edit', name: 'admin_ad_edit')]
    public function editAd(Ad $ad, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'L\'annonce a été modifiée avec succès.');

            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/edit_ad.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad,
        ]);
    }
    

    #[Route('/admin/user/{id}/delete', name: 'admin_user_delete')]
    public function deleteUser(User $user, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('admin_dashboard');
    }

    #[Route('/admin/Ad/{id}/delete', name: 'admin_ad_delete')]
    public function deleteAd(Ad $ad, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($ad);
        $entityManager->flush();

        return $this->redirectToRoute('admin_dashboard');
    }
}
