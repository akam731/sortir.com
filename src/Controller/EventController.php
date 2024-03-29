<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EventController extends AbstractController
{
    #[Route('/event', name: 'event_list')]
    public function list(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findBy([], ['startingDate' => 'DESC'], 10);

        return $this->render('event/list.html.twig', [
            "events" => $events
        ]);
    }

    #[Route('/event/details{id}', name: 'event_details')]
    public function details(int $id, EventRepository $eventRepository): Response
    {
        $event = $eventRepository->find($id);

        return $this->render('event/details.html.twig', [
            "event" => $event
        ]);
    }
/*
    #[Route('/event/create', name: 'event_create')]
    public function create(Request $request, EntityManagerInterface $repositoryManager): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
            if ($form->isSubmitted()&& $form->isValid()){
                $event->
            }

        $repositoryManager->persist($event);
        $repositoryManager->flush();

        return $this->render('event/create.html.twig', [
            'EventType'=>$form,
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
