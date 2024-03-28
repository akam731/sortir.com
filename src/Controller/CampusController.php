<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Form\CampusSearchType;
use App\Form\CampusType;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/campus')]
class CampusController extends AbstractController
{
    #[Route('/', name: 'app_campus_index', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, CampusRepository $campusRepository): Response
    {
        $campus = new Campus();
        $form = $this->createForm(CampusType::class, $campus);
        $form->handleRequest($request);

        $formCampusSearch = $this->createForm(CampusSearchType::class);
        $formCampusSearch->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($campus);
            $entityManager->flush();

            return $this->redirectToRoute('app_campus_index', [], Response::HTTP_SEE_OTHER);
        }

        $campuses = $campusRepository->findAll();

        if ($formCampusSearch->isSubmitted() && $formCampusSearch->isValid()) {
            $sql = $formCampusSearch->getData()['search'];
            $campuses = $campusRepository->rechercherObjetsParChaine($sql);
        }


        return $this->render('campus/index.html.twig', [
            'campuses' => $campuses,
            'campus' => $campus,
            'form' => $form,
            'searchForm' => $formCampusSearch->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_campus_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Campus $campus, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CampusType::class, $campus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_campus_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('campus/edit.html.twig', [
            'campus' => $campus,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_campus_delete', methods: ['POST'])]
    public function delete(Request $request, Campus $campus, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$campus->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($campus);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_campus_index', [], Response::HTTP_SEE_OTHER);
    }
}
