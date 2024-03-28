<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\CampusSearchType;
use App\Form\UserEditType;
use App\Form\UserType;
use App\Repository\CampusRepository;
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



    #[Route('/admin/user/', name: 'admin_user', methods: ['GET', 'POST'])]
    public function index(UserRepository $userRepository, Request $request): Response
    {
        $formCampusSearch = $this->createForm(CampusSearchType::class);
        $formCampusSearch->handleRequest($request);

        $users = $userRepository->findAll();

        if ($formCampusSearch->isSubmitted() && $formCampusSearch->isValid()) {
            $sql = $formCampusSearch->getData()['search'];
            $users = $userRepository->rechercherObjetsParChaine($sql);
        }

        return $this->render('user/index.html.twig', [
            'users' => $users,
            'searchForm' => $formCampusSearch->createView(),
        ]);
    }

    #[Route('/admin/user/{id}', name: 'admin_user_show', methods: ['GET'])]
    public function show(User $user, CampusRepository $campusRepository): Response
    {
        $campus = $campusRepository->find($user->getCampus());

        return $this->render('user/show.html.twig', [
            'user' => $user,
            'campus' => $campus,
        ]);
    }

    #[Route('/admin/user/new', name: 'admin_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/{id}/edit', name: 'admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_crud_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/user/{id}', name: 'admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_user');
    }
}
