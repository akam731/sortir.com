<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ImportUsersFormType;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use League\Csv\Exception;
use League\Csv\InvalidArgument;
use League\Csv\Reader;
use League\Csv\UnavailableStream;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('', name: 'home')]
    public function home(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @throws UnavailableStream
     * @throws InvalidArgument
     * @throws Exception
     */
    #[Route('/user/csv', name: 'home_addUserByCsv')]
    public function addUserByCsv(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, CampusRepository $campusRepository): Response
    {
        $form = $this->createForm(ImportUsersFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('csvFile')->getData();
            $csvReader = Reader::createFromPath($file, 'r');
            $csvReader->setDelimiter(';');
            $records = $csvReader->getRecords();
            $i = 0;
            foreach ($records as $record) {

                if($i != 0) {
                    $user = new User();
                    $user->setCampus($campusRepository->find($record[0]));
                    $user->setEmail($record[1]);
                    $user->setRoles([$record[2]]);
                    $user->setPassword($userPasswordHasher->hashPassword($user, $record[3]));
                    $user->setLastName($record[4]);
                    $user->setFirstName($record[5]);
                    $user->setPhone($record[6]);
                    $user->setAdministrator((bool)$record[7]);
                    $user->setActive((bool)$record[8]);
                    $user->setImgName($record[9]);
                    $user->setPseudo($record[10]);
                    $user->setToken($record[11]);
                    $entityManager->persist($user);
                }else {
                    $i++;
                }

            }
            $entityManager->flush();

            return $this->redirectToRoute('admin_user');
        }

        return $this->render('admin/importUsers.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
