<?php

namespace App\DataFixtures;

use App\Entity\Adherent;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

// les fixtures sont exécutées par défaut par ordre alphabétique or,
// il faut effectuer la UserFixtures avant AdherentFixtures d'où le DependentFixtureInterface
class AdherentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // use the factory to create a Faker\Generator instance
        $faker = Faker\Factory::create('fr_FR');

        //Création de 21 adhérents dont 3 non à jour de leur cotisation
        for($adh = 1; $adh <= 21; $adh++){
            $adherent = new Adherent();
            if($adh<19) {$adherent->setIsCotisation(true);}
            else {$adherent->setIsCotisation(false);};
            // on va chercher une référence de compte utilisateur
            $user = $this->getReference('user_'. $adh);
            $adherent->setUser($user);

            $manager->persist($adherent);
        }

        $manager->flush();
    }

    public function getDependencies(): array{
        return [UserFixtures::class];           // liste des fixtures devant être exécutées avant AdherentFixtures
    }
}