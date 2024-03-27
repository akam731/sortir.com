<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\UserEditType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{

    private UserPasswordHasherInterface $passwordEncoder;

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
    public function homeId(UserPasswordHasherInterface $userPasswordHasher,Request $request,EntityManagerInterface $entityManager,UserRepository $userRepository ,$id): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $user = $userRepository->find($id);

        if($user) {

            if ($id == $this->getUser()->getId()) {

                $form = $this->createForm(UserEditType::class, $user);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {


                    $user = $form->getData();
                    $imgNameFile = $form->get('imgName')->getData();

                    if ($imgNameFile) {
                        $newFilename = $user->getId().'.'.$imgNameFile->guessExtension();

                        try {
                            $imgNameFile->move(
                                $this->getParameter('kernel.project_dir') . '/public/img/profil',
                                $newFilename
                            );

                            $user->setImgName($newFilename);
                        } catch (FileException $e) {

                        }
                    }

                    $this->passwordEncoder = $userPasswordHasher;
                    $mdp = $user->getPassword();
                    $hashedPassword = $this->passwordEncoder->hashPassword($user, $mdp);
                    $user->setPassword($hashedPassword);


                    $entityManager->persist($user);
                    $entityManager->flush();

                    return $this->redirectToRoute('profil_home_id', ['id' => $user->getId()]);

                }
                return $this->render('user/editProfil.html.twig', [
                    'user' => $user,
                    'form' => $form->createView(),
                ]);

            } else {
                return $this->render('user/showProfil.html.twig', [
                    'user' => $user,
                ]);

            }

        }else{
            return $this->redirectToRoute('main_home');
        }

    }


    /* TODO: Organiser tout ça */




    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }



    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
