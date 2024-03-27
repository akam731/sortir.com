<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/event/', name: 'event_')]
class EventController extends AbstractController
{
    /********************************
     *****     CREATE EVENT     *****
     ********************************/

    #[Route('create_event', name: 'createEvent')]
    public function createEvent(Request $request,
                                EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        //detail eventuels de parametrage
        $eventForm = $this->createForm(EventType::class, $event);

        $eventForm->handleRequest($request);
        if($eventForm->isSubmitted() && $eventForm->isValid()){
            $entityManager->persist($event);
            $entityManager->flush();
            return $this->redirectToRoute('event_readEvent',[
                'id' => $event->getId()
            ]);
        }
        return $this->render('event/createEvent.html.twig',[
            'eventForm'=> $eventForm->createView()
        ]);
    }

    /********************************
     *****      READ EVENT      *****
     ********************************/

    #[Route('read-event/{id}', name: 'readEvent')]
    public function readEvent(EventRepository $eventRepository, int $id): Response
    {
       return $this->render('event/readEvent.html.twig',[

        ]);
    }

    /********************************
     *****     UPDATE EVENT     *****
     ********************************/

    #[Route('update-event', name: 'updateEvent')]
    public function updateEvent(): Response
    {
        return $this->render('event/updateEvent.html.twig',[

        ]);
    }

    /********************************
     *****     DELETE EVENT     *****
     ********************************/

    #[Route('delete-event', name: 'deleteEvent')]
    public function deleteEvent(): Response
    {
        return $this->render('event/deleteEvent.html.twig',[

        ]);
    }
}
