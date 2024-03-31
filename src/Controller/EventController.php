<?php

namespace App\Controller;

use App\data\EventSearch;
use App\Entity\Event;
use App\Form\EventSearchType;
use App\Form\EventType;
use App\Message\EventManager;
use App\Repository\EventRepository;
use App\Repository\EventSearchRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class EventController extends AbstractController
{
    /**
     * @throws \Exception
     */
    #[Route('/home', name: 'main_home')]
    public function list(MessageBusInterface $bus,EventRepository $eventRepository, Request $request, EntityManagerInterface $repositoryManager, EventSearchRepository $eventSearchRepository): Response
    {
        $bus->dispatch(new EventManager());

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }else{
            $user = $this->getUser();
        }


        $data = new EventSearch();
        $event = new Event();
        $event->setOrganiser($this->getUser());
        $form = $this->createForm(EventSearchType::class, $data, [
            'user' => $user,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()){

            $events = $eventSearchRepository->findSearch($data, $user);

        }else{

            $events = $eventRepository->findActive($user);

        }

        return $this->render('main/home.html.twig', [
            'EventSearchType'=>$form->createView(),
            'events' => $events,
        ]);

    }


    #[Route('/event/details', name: 'event_details_error')]
    public function detailsError(): Response{
        return $this->redirectToRoute('app_login');
    }

    #[Route('/event/details{id}', name: 'event_details')]
    public function details(int $id, EventRepository $eventRepository): Response
    {
        $event = $eventRepository->find($id);

        $user = $this->getUser();

        if (!$user OR !$event) {
            return $this->redirectToRoute('main_home');
        }

        $allowedStatus = ['En cours', 'Clôturée', 'Ouverte'];

        $status = $event->getStatus();

        if(!in_array($status, $allowedStatus) AND $status == "En création" AND $user !== $event->getOrganiser()){
            return $this->redirectToRoute('main_home');
        }

        $city = $event->getPlace()->getCity();

        return $this->render('event/details.html.twig', [
            "event" => $event,
            "city" => $city,
        ]);
    }

    #[Route('/event/create', name: 'event_create')]
    public function create(Request $request, EntityManagerInterface $repositoryManager): Response
    {
        $event = new Event();
        $event->setOrganiser($this->getUser());
        $event->setStatus('Ouverte');
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

            if ($form->isSubmitted()&& $form->isValid()){
                $repositoryManager->persist($event);
                $repositoryManager->flush();

                $this->addFlash('success', 'Sortie crée!');
                return $this->redirectToRoute('event_details', ['id' => $event->getId()]);
            }

        return $this->render('event/create.html.twig', [
            'EventType'=>$form->createView(),
        ]);
    }

    /*
    #[Route('/event/demo', name: 'event_demo')]
    public function demo(EntityManagerInterface $entityManager): Response
    {
        $event = new Event();

        $event->setName('cinéma');
        $event->setStartingDate(new \DateTime());
        $event->setDurationTime(new \DateTime());
        $event->setRegistrationEnd(new \DateTime());
        $event->setMaxRegistration('15');
        $event->setEventInformations('blablabla');
        $event->setStatus();
        $event->setOrganiser();

        dump($event);
        $entityManager->persist($event);
        $entityManager->flush();

        return $this->render('event/create.html.twig', [
        ]);
    }
    */
}
