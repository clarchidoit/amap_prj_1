<?php

namespace App\DataFixtures;

use App\Entity\Producteur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

// les fixtures sont exécutées par défaut par ordre alphabétique or,
// il faut effectuer la UserFixtures avant ProducteurFixtures d'où le DependentFixtureInterface
class ProducteurFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // use the factory to create a Faker\Generator instance
        $faker = Faker\Factory::create('fr_FR');

        //Création de 5 producteurs
        for($prod = 22; $prod <= 26; $prod++){
            $producteur = new Producteur();
            $producteur->setDescription($faker->text(20));
            // on va chercher une référence de compte utilisateur
            $user = $this->getReference('user_'. $prod);
            $producteur->setUser($user);

            $manager->persist($producteur);
        }

        $manager->flush();
    }

    public function getDependencies(): array{
        return [UserFixtures::class];           // liste des fixtures devant être exécutées avant AdherentFixtures
    }
}
