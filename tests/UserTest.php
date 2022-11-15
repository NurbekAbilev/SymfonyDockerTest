<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;

class UserTest extends ApiTestCase
{
    private EntityManager $entityManager;

    public function setUp(): void
    {
        parent::setUp();
        static::bootKernel();
        $this->entityManager = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    public function test_get_users()
    {
        $client = static::createClient();

        $user = new User();
        $user->setEmail('john_doe@mail.com');
        $user->setPassword('password');
        $user->setUsername('john_doe');



        // $userRepo = $this->mock(UserRepository::class);
        // $userRepo->expec

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $response = $client->request('GET', '/user?username=john');

        $responseArray = json_decode($response->getContent(), true);
        dd($responseArray);
    }
}
