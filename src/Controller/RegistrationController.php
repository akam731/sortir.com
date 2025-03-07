<?php

namespace App\Controller;

use App\Entity\ApiKeys;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{

    #[Route('/admin/user/new', name: 'admin_user_new')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, CampusRepository $campusRepository): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            if ($user->isAdministrator()){
                $user->setRoles(['ROLE_ADMIN']);
            }

            $randomBytes = openssl_random_pseudo_bytes(25);
            $token = bin2hex($randomBytes);

            $apiToken = new ApiKeys();
            $apiToken->setUser($user);
            $apiToken->setToken($token);

            $entityManager->persist($user);
            $entityManager->persist($apiToken);
            $entityManager->flush();



            // do anything else you need here, like send an email

            return $this->redirectToRoute('admin_user');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form,
        ]);
    }
}
