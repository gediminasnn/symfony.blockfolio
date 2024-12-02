<?php

namespace App\Tests\Controller\Asset;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

trait TestAuthenticationTrait
{
    private function createAuthenticatedClient(): KernelBrowser
    {
        $client = static::createClient();
        $container = static::getContainer();

        $entityManager = $container->get(EntityManagerInterface::class);
        $userRepository = $entityManager->getRepository(User::class);

        $user = $userRepository->findOneBy(['email' => 'testuser@example.com']);

        if (!$user) {
            $user = new User();
            $user->setEmail('testuser@example.com');

            // Use the password hasher to set the password
            /** @var UserPasswordHasherInterface $passwordHasher */
            $passwordHasher = $container->get(UserPasswordHasherInterface::class);
            $encodedPassword = $passwordHasher->hashPassword($user, 'password');
            $user->setPassword($encodedPassword);

            $entityManager->persist($user);
            $entityManager->flush();
        }

        /** @var JWTTokenManagerInterface $jwtManager */
        $jwtManager = $container->get(JWTTokenManagerInterface::class);

        $token = $jwtManager->create($user);

        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $token));

        return $client;
    }
}
