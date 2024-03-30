<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    #[Route('/mailer', name: 'app_mailer')]
    public function index(MailerInterface $mailer): Response
    {
        $email = (new Email())

            ->from('8a0fc97851ba3c@sandbox.smtp.mailtrap.io')
            ->to('alexandre.marteau63@gmail.com')
            ->subject('Test email')
            ->text('This is a test email sent from Symfony.');

        $mailer->send($email);


        return $this->render('mailer/index.html.twig', [
            'controller_name' => 'MailerController',
        ]);
    }
}
