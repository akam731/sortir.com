<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ConnectionController extends AbstractController
{
    #[Route('/connexion', name: 'connection_main')]
    public function index(): Response
    {
        return $this->render('connection/connexion.html.twig');
    }
}
