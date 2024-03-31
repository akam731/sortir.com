<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{

    #[Route('/main', name: 'main')]
    public function home()
    {
        return $this->render('main/home.html.twig');
    }

}
