<?php

namespace App\MessageHandler;

use App\Entity\Event;
use App\Message\EventManager;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class EventManagerHandler
{

    private EventRepository $eventRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(EventRepository $eventRepository, EntityManagerInterface $entityManager)
    {
        $this->eventRepository = $eventRepository;
        $this->entityManager = $entityManager;
    }

    public function __invoke(EventManager $message): void
    {

        $currentDate = new \DateTime();

        /* Cloture les events si il n'y à plus de places */

        $events = $this->eventRepository->findByState('Ouverte');
        $events[] = $this->eventRepository->findByState('Annulée');
        foreach ($events as $event) {
            $nbParticipant = $event->getParticipants()->count();
            if ($nbParticipant == $event->getMaxRegistration() || $event->getRegistrationEnd() < $currentDate){
                $event->setStatus('Clôturée');
            }
        }


        /* Historisation des sorties réalisées depuis plus d’un mois". */
        $events = $this->eventRepository->findByState('Terminée');
        foreach ($events as $event) {
            $date = $event->getStartingDate();
            $dateMore1Month = $date->modify('+1 month');
            if ($currentDate > $dateMore1Month){
                $event->setStatus('Historisée');
            }
        }



        $this->entityManager->flush();
    }
}
