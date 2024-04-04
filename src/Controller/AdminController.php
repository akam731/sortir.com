<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('', name: 'home')]
    public function home(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/user/csv', name: 'home_addUserByCsv')]
    public function addUserByCsv(Request $request): Response
    {
        $form = $this->createForm(ImportUsersFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Traitement du fichier CSV
            $file = $form->get('csvFile')->getData();
            $userManager->importUsersFromCsv($file);

            // Affichage d'un message de succès
            $this->addFlash('success', 'Users imported successfully.');

            // Redirection vers une autre page ou affichage d'une vue de confirmation
            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/import_users.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
