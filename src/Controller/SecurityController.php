<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/', name: 'app_login')]
    public function login(MailerInterface $mailer, AuthenticationUtils $authenticationUtils): Response
    {
        /*
        $email = (new Email())
            ->from('no.reply.sortir@gmail.com') // L'adresse e-mail expéditeur
            ->to('alexandre.marteau63@gmail.com') // L'adresse e-mail du destinataire
            ->subject('Test d\'envoi d\'e-mail avec Symfony') // Le sujet de l'e-mail
            ->text('Ceci est un test d\'envoi d\'e-mail avec Symfony.'); // Le contenu de l'e-mail (en texte brut)

        $mailer->send($email);
        */

        if ($this->getUser()) {
            return $this->redirectToRoute('main_home');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);

    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
