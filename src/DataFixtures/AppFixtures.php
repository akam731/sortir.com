<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Event;
use App\Entity\Place;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        /* Génération des campus */
        $campusNantes = new Campus();
        $campusNantes->setName('Nantes');
        $manager->persist($campusNantes);
        $campusNiort = new Campus();
        $campusNiort->setName('Niort');
        $manager->persist($campusNiort);
        $campusSaintHerblain = new Campus();
        $campusSaintHerblain->setName('Saint-Herblain');
        $manager->persist($campusSaintHerblain);
        $campusRennes = new Campus();
        $campusRennes->setName('Rennes');
        $manager->persist($campusRennes);



        /* Génération des city */
        $cityNantes = new City();
        $cityNantes->setName('Nantes');
        $cityNantes->setZipCode("44000");
        $manager->persist($cityNantes);
        $cityNiort = new City();
        $cityNiort->setName('Niort');
        $cityNiort->setZipCode("79000");
        $manager->persist($cityNiort);
        $cityRennes = new City();
        $cityRennes->setName('Rennes');
        $cityRennes->setZipCode("35000");
        $manager->persist($cityRennes);
        $citySaintHerblain = new City();
        $citySaintHerblain->setName('Saint-Herblain');
        $citySaintHerblain->setZipCode("44800");
        $manager->persist($citySaintHerblain);


        /* Génération des places */
        $placeCinema = new Place();
        $placeCinema->setCity($cityNantes);
        $placeCinema->setName('Cinema');
        $placeCinema->setStreet("79 Bd de l'Égalité");
        $placeCinema->setLatitude("47.2471238046294");
        $placeCinema->setLongitude("-1.5784673388867876");
        $manager->persist($placeCinema);
        $place1Niort = new Place();
        $place1Niort->setCity($cityNiort);
        $place1Niort->setName('Parc de la Breche');
        $place1Niort->setStreet("Rue Henri Barbusse");
        $place1Niort->setLatitude("46.320556");
        $place1Niort->setLongitude("-0.454722");
        $manager->persist($place1Niort);
        $place2Rennes = new Place();
        $place2Rennes->setCity($cityRennes);
        $place2Rennes->setName('Le Parc du Thabor');
        $place2Rennes->setStreet("Place Saint-Mélaine");
        $place2Rennes->setLatitude("48.111389");
        $place2Rennes->setLongitude("-1.673611");
        $manager->persist($place2Rennes);
        $place3SaintHerblain = new Place();
        $place3SaintHerblain->setCity($citySaintHerblain);
        $place3SaintHerblain->setName('Zénith Nantes Métropole');
        $place3SaintHerblain->setStreet("ZAC d'Ar Mor");
        $place3SaintHerblain->setLatitude("47.2266");
        $place3SaintHerblain->setLongitude("-1.6199");
        $manager->persist($place3SaintHerblain);
        $place4Niort = new Place();
        $place4Niort->setCity($cityNiort);
        $place4Niort->setName('Musée Bernard d\'Agesci');
        $place4Niort->setStreet("26 Avenue de Limoges");
        $place4Niort->setLatitude("46.322743");
        $place4Niort->setLongitude("-0.464507");
        $manager->persist($place4Niort);
        $place5Rennes = new Place();
        $place5Rennes->setCity($cityRennes);
        $place5Rennes->setName('Place des Lices');
        $place5Rennes->setStreet("Place des Lices");
        $place5Rennes->setLatitude("48.114167");
        $place5Rennes->setLongitude("-1.679722");
        $manager->persist($place5Rennes);


        /* Création des users */
        $user = new User();
        $user->setCampus($campusNantes);
        $user->setEmail('user' . '@example.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword(password_hash('111111', PASSWORD_DEFAULT));
        $user->setLastName('Alex');
        $user->setFirstName('ou');
        $user->setPhone('1234567809');
        $user->setAdministrator(true);
        $user->setActive(true);
        $user->setImgName(null);
        $user->setPseudo('alexou');
        $manager->persist($user);
        $user2 = new User();
        $user2->setCampus($campusNiort);
        $user2->setEmail('user2@example.com');
        $user2->setRoles(['ROLE_ADMIN']);
        $user2->setPassword(password_hash('111111', PASSWORD_DEFAULT));
        $user2->setLastName('Papy');
        $user2->setFirstName('lataupe');
        $user2->setPhone('1234567890');
        $user2->setAdministrator(true);
        $user2->setActive(true);
        $user2->setImgName(null);
        $user2->setPseudo('papy');
        $manager->persist($user2);
        $user3 = new User();
        $user3->setCampus($campusRennes);
        $user3->setEmail('user3@example.com');
        $user3->setRoles(['ROLE_ADMIN']);
        $user3->setPassword(password_hash('111111', PASSWORD_DEFAULT));
        $user3->setLastName('au');
        $user3->setFirstName('drey');
        $user3->setPhone('1234560789');
        $user3->setAdministrator(true);
        $user3->setActive(true);
        $user3->setImgName(null);
        $user3->setPseudo('audrey');
        $manager->persist($user3);
        $user4 = new User();
        $user4->setCampus($campusSaintHerblain);
        $user4->setEmail('user4@example.com');
        $user4->setRoles([]);
        $user4->setPassword(password_hash('111111', PASSWORD_DEFAULT));
        $user4->setLastName('titi');
        $user4->setFirstName('titi');
        $user4->setPhone('1234560789');
        $user4->setAdministrator(false);
        $user4->setActive(true);
        $user4->setImgName(null);
        $user4->setPseudo('titi');
        $manager->persist($user4);
        $user5 = new User();
        $user5->setCampus($campusNantes);
        $user5->setEmail('user5@example.com');
        $user5->setRoles([]);
        $user5->setPassword(password_hash('111111', PASSWORD_DEFAULT));
        $user5->setLastName('Victor');
        $user5->setFirstName('del');
        $user5->setPhone('1234560789');
        $user5->setAdministrator(false);
        $user5->setActive(false);
        $user5->setImgName(null);
        $user5->setPseudo('Victor');
        $manager->persist($user5);


        /* Création des events */

        $jourUn = new \DateTime();
        $date = array();
        for ($i = -3; $i <= 9; $i++) {
            $jour = clone $jourUn;
            $jour->modify("$i days");
            $date[] = $jour;
        }



        $event1 = new Event();
        $event1->setOrganiser($user);
        $event1->setPlace($placeCinema);
        $event1->setName('Sortie au ciné');
        $event1->setStartingDate($date[3]);
        $event1->setDurationTime($date[8]);
        $event1->setRegistrationEnd($date[0]);
        $event1->setMaxRegistration(7);
        $event1->setEventInformations('Petite sortie au ciné pour se dégourdir les jambes !');
        $event1->addParticipant($user);
        $event1->addParticipant($user2);
        $event1->addParticipant($user3);
        $event1->setStatus('En création');
        $manager->persist($event1);
        $event2= new Event();
        $event2->setOrganiser($user2);
        $event2->setPlace($place1Niort);
        $event2->setName('Sortie a Niort');
        $event2->setStartingDate($date[4]);
        $event2->setDurationTime($date[5]);
        $event2->setRegistrationEnd($date[1]);
        $event2->setMaxRegistration(10);
        $event2->setEventInformations('Petite sortie tranquillou !');
        $event2->addParticipant($user2);
        $event2->addParticipant($user4);
        $event2->addParticipant($user3);
        $event2->setStatus('Ouverte');
        $manager->persist($event2);
        $event3 = new Event();
        $event3->setOrganiser($user3);
        $event3->setPlace($place2Rennes);
        $event3->setName('Événement 3');
        $event3->setStartingDate($date[3]);
        $event3->setDurationTime($date[5]);
        $event3->setRegistrationEnd($date[2]);
        $event3->setMaxRegistration(8);
        $event3->setEventInformations('Description de l\'événement 3');
        $event3->addParticipant($user3);
        $event3->addParticipant($user);
        $event3->addParticipant($user5);
        $event3->setStatus('Cloturée');
        $manager->persist($event3);
        $event4 = new Event();
        $event4->setOrganiser($user4);
        $event4->setPlace($place3SaintHerblain);
        $event4->setName('Événement 4');
        $event4->setStartingDate($date[5]);
        $event4->setDurationTime($date[9]);
        $event4->setRegistrationEnd($date[4]);
        $event4->setMaxRegistration(12);
        $event4->setEventInformations('Description de l\'événement 4');
        $event4->addParticipant($user4);
        $event4->addParticipant($user3);
        $event4->addParticipant($user5);
        $event4->setStatus('En cours');
        $manager->persist($event4);
        $event5 = new Event();
        $event5->setOrganiser($user5);
        $event5->setPlace($place4Niort);
        $event5->setName('Événement 5');
        $event5->setStartingDate($date[3]);
        $event5->setDurationTime($date[6]);
        $event5->setRegistrationEnd($date[1]);
        $event5->setMaxRegistration(10);
        $event5->setEventInformations('Description de l\'événement 5');
        $event5->addParticipant($user5);
        $event5->setStatus('Terminée');
        $manager->persist($event5);
        $event6 = new Event();
        $event6->setOrganiser($user);
        $event6->setPlace($place5Rennes);
        $event6->setName('Événement 6');
        $event6->setStartingDate($date[1]);
        $event6->setDurationTime($date[7]);
        $event6->setRegistrationEnd($date[0]);
        $event6->setMaxRegistration(15);
        $event6->setEventInformations('Description de l\'événement 6');
        $event6->addParticipant($user);
        $event6->addParticipant($user4);
        $event6->setStatus('Annulée');
        $manager->persist($event6);
        $event7 = new Event();
        $event7->setOrganiser($user2);
        $event7->setPlace($place1Niort);
        $event7->setName('Événement 7');
        $event7->setStartingDate($date[4]);
        $event7->setDurationTime($date[5]);
        $event7->setRegistrationEnd($date[3]);
        $event7->setMaxRegistration(9);
        $event7->setEventInformations('Description de l\'événement 7');
        $event7->addParticipant($user2);
        $event7->addParticipant($user3);
        $event7->addParticipant($user5);
        $event7->setStatus('Historisée');
        $manager->persist($event7);
        $event8 = new Event();
        $event8->setOrganiser($user2);
        $event8->setPlace($place1Niort);
        $event8->setName('Événement 7');
        $event8->setStartingDate($date[8]);
        $event8->setDurationTime($date[9]);
        $event8->setRegistrationEnd($date[7]);
        $event8->setMaxRegistration(3);
        $event8->setEventInformations('Description de l\'événement 7');
        $event8->addParticipant($user2);
        $event8->addParticipant($user3);
        $event8->addParticipant($user5);
        $event8->setStatus('Ouverte');
        $manager->persist($event8);

        $manager->flush();
    }
}
