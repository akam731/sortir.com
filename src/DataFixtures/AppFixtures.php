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


        $manager->flush();
    }
}
