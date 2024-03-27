<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Reservation;
use App\Repository\ClientRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

        public function __construct(private UserPasswordHasherInterface $userPasswordHasher) {
        
        }


    public function load(ObjectManager $manager): void
    {


        $client = new Client();
        $client->setEmail('hamza@gmail.com')
        ->setFirstName('Hamza')
        ->setLastName('Bouh')
        ->setPassword('94867340')
        ->setPassword(
            $this->userPasswordHasher->hashPassword(
            $client,
            '94867340'
            
        )
    )
        ->setPhoneNumber('0606060606')
        ->setAddress('Casablanca')
        ->setRoles(['ROLE_CLIENT']);

        $manager->persist($client);


            for ($i = 0; $i < 2; $i++) {
              $reservation = new Reservation();
              $reservation->setDepAddress('DepAddress'.$i)
                ->setDestination('ArrAddress'.$i)
                ->setNbPassengers(3)
                ->setClient($client)
                ->setReservationDatetime(new \DateTimeImmutable());
                $manager->persist($reservation);
            }

             $manager->flush();
    }
}
