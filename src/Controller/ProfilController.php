<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil/', name: 'profil_home')]
    public function home(): Response
    {
        if ($this->getUser()) {
            $userId = $this->getUser()->getId();
            return $this->redirectToRoute('profil_home_id', ['id' => $userId]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/profil/{id}', name: 'profil_home_id')]
    public function homeId($id): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if($id == $this->getUser()->getId()){
            return $this->render('profil/editProfil.html.twig', [
                'user' => $this->getUser(),
            ]);
        }else{
            return $this->render('profil/showProfil.html.twig', [
                'user' => $this->getUser(),
            ]);
        }

    }
}
