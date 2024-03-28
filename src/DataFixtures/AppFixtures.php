<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        for ($i = 1; $i <= 5; $i++) {

            $




            $user = new User();




            $user->setCampusId(1); // Campus ID




            $user->setEmail('user' . $i . '@example.com'); // Email
            $user->setRoles(['ROLE_USER']); // Rôles (au format JSON)
            $user->setPassword(password_hash('password' . $i, PASSWORD_DEFAULT)); // Mot de passe




            $user->setLastName('LastName' . $i); // Nom de famille




            $user->setFirstName('FirstName' . $i); // Prénom




            $user->setPhone('123456789' . $i); // Numéro de téléphone




            $user->setAdministrator(false); // Administrateur (false = non, true = oui)




            $user->setActive(true); // Actif (false = non, true = oui)
            $user->setImgName(null); // Nom du fichier image
            $user->setPseudo('pseudo' . $i); // Pseudo

        $manager->flush();
    }
}
