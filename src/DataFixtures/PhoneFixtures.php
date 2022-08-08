<?php

namespace App\DataFixtures;

use App\Entity\Phone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PhoneFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $phone = new Phone();

        $phone->setBrand('XIAOMI')
            ->setDescription('Achetez le Redmi Note 7 sur le site officiel. La nouvelle expérience du haut-de-gamme. Capturez tout avec 48 MP. 4000mAh(typ). Batterie ultra-haute capacité.')
            ->setModel('Redmi Note 7')
            ->setPrice(165.85);

        $manager->persist($phone);

        $phone1 = new Phone();

        $phone1->setBrand('Huawei')
            ->setDescription('Le Huawei P20 Lite est le petit frère du Huawei P20 reprenant quelques fonctionnalités disponibles sur son grand-frère à un prix plus abordable.')
            ->setModel('P20 Lite')
            ->setPrice(188.34);

        $manager->persist($phone1);

        $phone2 = new Phone();

        $phone2->setBrand('Samsung')
            ->setDescription('Si pour vous les Galaxy S10 sont vraiment trop onéreux, alors peut-être que le Galaxy A50 pourrait être le bon choix.')
            ->setModel('Galaxy A50')
            ->setPrice(275.99);

        $manager->persist($phone2);

        $phone3 = new Phone();

        $phone3->setBrand('Huawei')
            ->setDescription('Le Huawei P Smart 2019 est un smartphone de milieu de gamme annoncé en décembre 2018 et est le successeur de la gamme P8 Lite.')
            ->setModel('P Smart 2019')
            ->setPrice(164.90);

        $manager->persist($phone3);

        $phone4 = new Phone();

        $phone4->setBrand('Apple')
            ->setDescription('Sans se presser, Apple fait évoluer son concept formel d\'iPhone 6 lancé en 2014. Suppression de la prise mini-Jack.')
            ->setModel('Iphone 7')
            ->setPrice(254.00);

        $manager->persist($phone4);

        $phone5 = new Phone();

        $phone5->setBrand('Honor')
            ->setDescription('Au final, ce Honor 8X offre des performances tout à fait honnêtes dans un corps plutôt élégant. ')
            ->setModel('8X')
            ->setPrice(199.00);

        $manager->persist($phone5);

        $phone6 = new Phone();

        $phone6->setBrand('Nokia')
                ->setDescription('Avec ses formes courbées et ergonomiques ainsi que son clavier Island qui facilite la saisie et la composition.')
                ->setModel('105')
                ->setPrice(19.90);

        $manager->persist($phone6);


        $manager->flush();
    }
}
