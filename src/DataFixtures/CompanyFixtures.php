<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\UserCompany;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CompanyFixtures extends Fixture
{
    private $userPasswordHasher;
    
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création de plusieurs utilisateurs
        $faker = Factory::create();


        // Création de deux société Orange et Sfr
        $company = new Company();
        $company->setEmail("orange@orange.fr");
        $company->setRoles(["ROLE_ADMIN"]);
        $company->setPassword($this->userPasswordHasher->hashPassword($company, "password"));
        $manager->persist($company);

        for ($i = 0; $i < 15; $i++) {
            $userCompany = new UserCompany;
            $userCompany->setFirstName($faker->firstName());
            $userCompany->setLastName($faker->lastName());
            $userCompany->setCompany($company);
            
            $manager->persist($userCompany); 
        }

        $company1 = new Company();
        $company1->setEmail("sfr@sfr.fr");
        $company1->setRoles(["ROLE_ADMIN"]);
        $company1->setPassword($this->userPasswordHasher->hashPassword($company1, "password"));
        $manager->persist($company1);

        for ($i = 0; $i < 15; $i++) {
            $userCompany = new UserCompany;
            $userCompany->setFirstName($faker->firstName());
            $userCompany->setLastName($faker->lastName());
            $userCompany->setCompany($company1);
            
            $manager->persist($userCompany); 
        }

        

        $manager->flush();
    }
}
