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



        $manager->flush();
    }
}
