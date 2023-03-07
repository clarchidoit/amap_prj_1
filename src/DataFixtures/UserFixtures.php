<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker;

class UserFixtures extends Fixture
{
    private $compteur = 1;   // pour compter les utilisateurs créés
    
    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder,
        private SluggerInterface $slugger
    ) {}

    public function load(ObjectManager $manager): void
    {
        // création du compte administrateur, il n'y en a en général qu'un
        $admin = new User();
        $admin->setEmail('admin@amap.fr');
        $admin->setNom('Admin');
        $admin->setPrenom('Jojo');
        $admin->setAdresse('12 rue amap');
        $admin->setCodePostal('44230');
        $admin->setCommune('St Sébastien');
        $admin->setTelPortable('07 33 27 15 16');
        $admin->setTel2('02 55 27 69 16');
        $admin->setIsValide(true);
        $admin->setIsMailValide(true);
        $admin->setPassword($this->passwordEncoder->hashPassword($admin, 'admin'));   // le user est $admin et le password admin
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);
        $this->addReference('user_'.$this->compteur, $admin);
        $this->compteur++;

        $faker = Faker\Factory::create('fr_FR');    // pour créer de fausses données "à la française"
        // Création de 20 comptes adhérents validés par le responsable des inscriptions à l'AMAP
        for($usr=1; $usr<=20; $usr++){
            $user = new User();
            $user->setEmail($faker->email);
            $user->setNom($faker->lastName);
            $user->setPrenom($faker->firstName);
            $user->setAdresse($faker->streetAddress);
            $user->setCodePostal(str_replace(' ', '',$faker->postcode));  //pour enlever les espaces dans certains postcodes car on l'a limité à 5 caractères
            $user->setCommune($faker->city);
            $user->setTelPortable($faker->phoneNumber);
            $user->setIsValide(true);
            $user->setIsMailValide(true);
            $user->setPassword($this->passwordEncoder->hashPassword($user, 'secret'));
            $user->setRoles(['ROLE_ADHERENT']);
            // dump($user);     pour visualiser $user s'il y a des problèmes

            $manager->persist($user);
            $this->addReference('user_'.$this->compteur, $user);    // stocke en mémoire des références
            $this->compteur++;
        }
        
        // Création de 4 comptes non encore validés par le responsable des inscriptions à l'AMAP
        for($usr=1; $usr<=4; $usr++){
            $user = new User();
            $user->setEmail($faker->email);
            $user->setNom($faker->lastName);
            $user->setPrenom($faker->firstName);
            $user->setAdresse($faker->streetAddress);
            $user->setCodePostal(str_replace(' ', '',$faker->postcode));  //pour enlever les espaces dans certains postcodes car on l'a limité à 5 caractères
            $user->setCommune($faker->city);
            $user->setTelPortable($faker->phoneNumber);
            //$user->setIsValide(false);         // car, dans User.php, on a mis : private ?bool $is_valide = false;
            $user->setIsMailValide(true);
            $user->setPassword($this->passwordEncoder->hashPassword($user, 'secret'));
            $user->setRoles(['ROLE_EN_ATTENTE']);
            // dump($user);     pour visualiser $user s'il y a des problèmes
            
            $manager->persist($user);
        }

        // Création des comptes utilisateurs de 5 producteurs
        for($usr=1; $usr<=5; $usr++){
            $user = new User();
            $user->setEmail($faker->email);
            $user->setNom($faker->lastName);
            $user->setPrenom($faker->firstName);
            $user->setAdresse($faker->streetAddress);
            $user->setCodePostal(str_replace(' ', '',$faker->postcode));  //pour enlever les espaces dans certains postcodes car on l'a limité à 5 caractères
            $user->setCommune($faker->city);
            $user->setTelPortable($faker->phoneNumber);
            //$user->setIsValide(false);
            $user->setIsMailValide(true);
            $user->setPassword($this->passwordEncoder->hashPassword($user, 'secret'));
            $user->setRoles(['ROLE_PRODUCTEUR']);
            // dump($user);     pour visualiser $user s'il y a des problèmes
            $manager->persist($user);

            $this->addReference('user_'.$this->compteur, $user);    // stocke en mémoire des références
            
            $this->compteur++;
        }

        //dump($this->compteur);        //pour visualiser this-$compteur pour savoir ce qu'il vaut (il vaut 31)

        $manager->flush();
    }
}
