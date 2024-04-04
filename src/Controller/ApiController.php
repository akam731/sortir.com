<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\DateTime;

class ApiController extends AbstractController
{
    #[Route('/api/events/', name: 'api_events')]
    public function index(Request $request,EventRepository $eventRepository, UserRepository $userRepository): Response
    {

        $state = $request->query->get('state');
        $date = $request->query->get('date');
        $token = $request->query->get('apiKey');

        $user = $userRepository->findBy(['token' => $token]);
        if (!$user){
            return new Response('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }
        $allEvents = $eventRepository->findAll();
        $events = [];
        $allowedStates = ['Ouverte','Clôturée','Annulée','En cours'];

        /* Gestion de la selection par état */
        if ($state !== null && in_array($state, $allowedStates)){
            foreach ($allEvents as $event){
                if ($event->getStatus() == $state){
                    $events[] = $event;
                }
            }
        }else{
            foreach ($allEvents as $event){
                if (in_array($event->getStatus(), $allowedStates)){
                    $events[] = $event;
                }
            }
        }

        /* Gestion de la dâte */
        if ($date != null){
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $date);
            if ($date !== false) {
                $newTab = $events;
                $events = [];
                foreach ($newTab as $event){
                    if ($event->getStartingDate() > $date){
                        $events[] = $event;
                    }
                }
            }
        }

        $data =  [];
        foreach ($events as $event) {
            $data[] = [
                'id' => $event->getId(),
                'organiser_id' => $event->getOrganiser()->getId(),
                'place_id' => $event->getPlace()->getId(),
                'name' => $event->getName(),
                'starting_date' => $event->getStartingDate()->format('Y-m-d H:i:s'),
                'duration_time' => $event->getDurationTime(),
                'registration_end' => $event->getRegistrationEnd()->format('Y-m-d H:i:s'),
                'max_registration' => $event->getMaxRegistration(),
                'event_informations' => $event->getEventInformations(),
                'status' => $event->getStatus(),
                'cancellation_reason' => $event->getConcellationReason(),
            ];
        }
        return $this->json($data);
    }


}
