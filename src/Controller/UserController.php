<?php

namespace App\Controller;


use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\CampusSearchType;
use App\Form\RegistrationFormType;
use App\Form\ResetPasswordProcessType;
use App\Form\ResetPasswordType;
use App\Form\UserEditType;
use App\Form\UserType;
use App\Repository\CampusRepository;
use App\Repository\ResetPasswordRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
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
    public function homeId(UserPasswordHasherInterface $userPasswordHasher,Request $request,EntityManagerInterface $entityManager,UserRepository $userRepository ,$id): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $user = $userRepository->find($id);

        if($user) {

            if ($id == $this->getUser()->getId()) {

                /*
                ATTENTION ICI MODIFICATION : j'ajoute le 'password_encoder' dont il ya besoin
                dans la logique implantée dans UserEditType
                */

                $form = $this->createForm(UserEditType::class, $user, [
                    'password_hasher' => $userPasswordHasher,
                ]);
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

                    $plainPassword = $form->get('password')->getData();
                    dump($plainPassword);
                    if (!empty($plainPassword)) {
                        $hashedPassword = $userPasswordHasher->hashPassword($user, $plainPassword);
                        $user->setPassword($hashedPassword);
                    }


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

    #[Route('/admin/{id}/edit', name: 'admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($user->isAdministrator()){
                $user->setRoles(["ROLE_ADMIN"]);
            }else{
                $user->setRoles([]);
            }
            $entityManager->flush();
            return $this->redirectToRoute('admin_user', [], Response::HTTP_SEE_OTHER);
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

    /*   Gestion de la rénitialisation de mots de passes   */

    /**
     * @throws TransportExceptionInterface
     */
   #[Route('/resetPassword', name: 'user_reset_password')]
    public function resetPassword(MailerInterface $mailer,Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository,  ResetPasswordRepository $passwordRepository): Response
    {


        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $mail = $form->getData()['email'];
            $user = $userRepository->findOneBy(['email' => $mail]);


            if(!$user){
                $error = 'Votre email n\' est pas relié à un compte !';
                return $this->render('security/resetPassword.html.twig',[
                    'form' => $form->createView(),
                    'error' => $error,
                ]);
            }

            /* Génére un token de 250 caractères */
            $randomBytes = openssl_random_pseudo_bytes(125);
            $token = bin2hex($randomBytes);

            $timeDelay = new \DateTime();
            $timeDelay->modify('+1 day');

            $passwordReseting = new ResetPassword();
            $passwordReseting->setUser($user);
            $passwordReseting->setToken($token);
            $passwordReseting->setValideDelay($timeDelay);

            $passwordEntity = $passwordRepository->findBy(['user' => $user]);
            if ($passwordEntity){
                foreach ($passwordEntity as $entity) {
                    $entityManager->remove($entity);
                }
                $entityManager->flush();
            }

            $entityManager->persist($passwordReseting);
            $entityManager->flush();

            $email = (new Email())
                ->from('no.reply.sortir@gmail.com')
                ->to($mail)
                ->subject('Test email')
                ->html("
                    <h1>Sortir.com</h1>
                    <br>
                    <h2>Réinitialisation du mot de passe</h2>
                    <br>
                    <h2>Voici le lien pour réinitialiser votre mot de passe :</h2>
                    <br>
                    <a href='http://localhost:8087/sortir.com/public/resetPassword/$token'>Réinitialiser mon mot de passe</a>
                    
                "
                );

            $mailer->send($email);

            return $this->render('security/resetPassword.html.twig',[
                'validateAction' => true,
            ]);

        }

        return $this->render('security/resetPassword.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    #[Route('/resetPassword/{token}', name: 'user_reset_password_process')]
    public function resetProcess($token, Request $request, EntityManagerInterface $entityManager, ResetPasswordRepository $passwordRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {

        $passwordEntity = $passwordRepository->findBy(['token' => $token]);
        foreach ($passwordEntity as $password){
            $passwordEntity = $password;
        }

        $currentDate = new \DateTime();

        if (!$passwordEntity or $currentDate > $passwordEntity->getValideDelay()){
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ResetPasswordProcessType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $user = $passwordEntity->getUser();

            $password = $form->getData()['password'];

            $hashedPassword = $userPasswordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);
            $entityManager->remove($passwordEntity);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');

        }

        return $this->render('security/resetPasswordProcess.html.twig',[
            'form' => $form->createView(),
        ]);
    }

}
