<?php

namespace App\Controller;

use App\data\EventSearch;
use App\Entity\Event;
use App\Entity\Place;
use App\Form\EventSearchType;
use App\Form\EventType;
use App\Form\PlaceType;
use App\Message\EventManager;
use App\Repository\CityRepository;
use App\Repository\EventRepository;
use App\Repository\EventSearchRepository;
use App\Repository\PlaceRepository;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
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

    /**
     * @throws \Exception
     */
    #[Route('/event/create', name: 'event_create')]
    public function create(Session $session,Request $request, EntityManagerInterface $repositoryManager, PlaceRepository $placeRepository, CityRepository $cityRepository): Response
    {

        $isPlaceCreated = "false";

        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('main_home');
        }

        $place = new Place();
        $placeForm = $this->createForm(PlaceType::class, $place);
        $placeForm->handleRequest($request);
        if ($placeForm->isSubmitted()){
            $session->set('isPlaceCreated', true);
        }else{
            $session->set('isPlaceCreated', false);
        }
        if ($placeForm->isSubmitted() && $placeForm->isValid()) {


            $repositoryManager->persist($place);
            $repositoryManager->flush();

            $isPlaceCreated = "true";

            $session->set('isPlaceCreated', false);
        }

            $event = new Event();
        $event->setOrganiser($this->getUser());
        $event->setStatus('Ouverte');
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()){

                if ($request->request->has('registerEvent')) {
                    $event->setStatus('En création');
                } elseif ($request->request->has('publish')) {
                    $event->setStatus('Ouverte');
                }

                $repositoryManager->persist($event);
                $repositoryManager->flush();

                $this->addFlash('success', 'Sortie crée!');
                return $this->redirectToRoute('event_details', ['id' => $event->getId()]);
            }

        $places = $placeRepository->findAll();
        $placesData = [];
        foreach ($places as $place) {
            $placesData[] = [
                'id' => $place->getId(),
                'City_id' => $place->getCity()->getId(),
                'City_name' => $place->getCity()->getName(),
                'zip_code' => $place->getCity()->getZipCode(),
                'name' => $place->getName(),
                'street' => $place->getStreet(),
                'latitude' => $place->getLatitude(),
                'longitude' => $place->getLongitude(),
            ];
        }

        $cities = $cityRepository->findAll();

        return $this->render('event/create.html.twig', [
            'form'=>$form->createView(),
            'user' => $user,
            'places' => $placesData,
            'cities' => $cities,
            'placeForm' => $placeForm,
            'isPlaceCreated' => $isPlaceCreated,
        ]);
    }



    #[Route('/event/publish{id}', name: 'event_publish')]
    public function publish(int $id, EventRepository $eventRepository, EntityManagerInterface $repositoryManager,): Response
    {
        $event = $eventRepository->find($id);
        $user = $this->getUser();
        if (!$user OR !$event) {
            return $this->redirectToRoute('main_home');
        }
        $status = $event->getStatus();

        if($status == "En création" AND $user === $event->getOrganiser()){

            $event->setStatus('Ouverte');
            $repositoryManager->flush();

        }
        return $this->redirectToRoute('main_home');
    }


    #[Route('/event/join{id}', name: 'event_join')]
    public function join(int $id, EventRepository $eventRepository, EntityManagerInterface $repositoryManager,): Response
    {
        $event = $eventRepository->find($id);
        $user = $this->getUser();
        if (!$user OR !$event) {
            return $this->redirectToRoute('main_home');
        }
        $status = $event->getStatus();
        if (
            $status === "Ouverte" &&
            $event->getRegistrationEnd() > new DateTime() &&
            $event->getMaxRegistration() > $event->getParticipants()->count() &&
            !$event->getParticipants()->contains($user)
        ) {
            $event->addParticipant($user);
            $repositoryManager->flush();
        }
        return $this->redirectToRoute('event_details', ['id' => $event->getId() ]);
    }

    #[Route('/event/leave{id}', name: 'event_leave')]
    public function leave(int $id, EventRepository $eventRepository, EntityManagerInterface $repositoryManager,): Response
    {
        $event = $eventRepository->find($id);
        $user = $this->getUser();
        if (!$user OR !$event) {
            return $this->redirectToRoute('main_home');
        }
        $status = $event->getStatus();
        if (
            $status === "Ouverte" &&
            $event->getStartingDate() > new DateTime() &&
            $event->getParticipants()->contains($user)
        ) {
            return $this->render();
        }else{
            return $this->redirectToRoute('main_home');
        }
    }


    #[Route('/event/cancellation{id}', name: 'event_cancellation')]
    public function cancellation(int $id, EventRepository $eventRepository, EntityManagerInterface $repositoryManager,): Response
    {
        $event = $eventRepository->find($id);
        $user = $this->getUser();
        if (!$user OR !$event) {
            return $this->redirectToRoute('main_home');
        }
        $status = $event->getStatus();
        if (
            $status === "Ouverte" &&
            $event->getStartingDate() > new DateTime() &&
            $event->getOrganiser() === $user
        ) {
            $event->removeParticipant($user);
            $repositoryManager->flush();
        }
        return $this->redirectToRoute('event_details', ['id' => $event->getId()]);
    }


}
