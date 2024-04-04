<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiController extends AbstractController
{
    #[Route('/api/events/{token}', name: 'api_events')]
    public function index($token,EventRepository $eventRepository, UserRepository $userRepository): Response
    {

        $user = $userRepository->findBy(['token' => $token]);

        if (!$user){
            return new Response('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $events = $eventRepository->findAll();

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
