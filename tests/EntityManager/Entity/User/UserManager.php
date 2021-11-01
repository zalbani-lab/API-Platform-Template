<?php

declare(strict_types=1);

namespace App\Tests\EntityManager\Entity\User;

use App\Entity\User;
use App\Tests\EntityManager\TestEntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class UserManager implements TestEntityManagerInterface
{
    use UserFunctions;

    private string $userPayload = '{"email":"%s", "password":"%s"}';

    private ObjectManager $objectManager;
    private UserPasswordEncoder $passwordEncoder;
    private \Faker\Generator $faker;

    public function __construct(ObjectManager $objectManager, UserPasswordEncoder $passwordEncoder)
    {
        $this->objectManager = $objectManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = Factory::create();
    }

    public function deleteOne(int $id): void
    {
        $userToDelete = $this->getOne($id);
        if (null !== $userToDelete && $userToDelete instanceof User) {
            $this->objectManager->remove($userToDelete);
            $this->objectManager->flush();
            $this->objectManager->clear();
        }
    }

    public function getOne(int $id): ?User
    {
        $result = $this->objectManager
            ->getRepository(User::class)
            ->find($id);
        if (null !== $result && $result instanceof User) {
            return $result;
        }

        return null;
    }

    public function createOne(?array $options = null): User
    {
        if (null !== $options) {
            switch ($options['role']) {
                case 'REDACTOR':
                    return $this->createOneUser('ROLE_REDACTOR');
                case 'ADMIN':
                    return $this->createOneUser('ROLE_ADMIN');
                default:
                    return $this->createOneUser('ROLE_USER');
            }
        } else {
            return $this->createOneUser('ROLE_USER');
        }
    }

    private function createOneUser(string $role): User
    {
        $userTemp = new User();

        $randomEmail = $this->getRandomEmail();
        $randomPassword = $this->getRandomPassword();
        $hashPassword = $this->hashPassword($userTemp, $randomPassword);

        $userTemp->setPassword($hashPassword)
            ->setEmail($randomEmail)
            ->setUpdatedAt(null)
            ->setRoles([$role]);

        $this->objectManager->persist($userTemp);
        $this->objectManager->flush();

        $userTemp = $this->objectManager
            ->getRepository(User::class)
            ->findOneBy(['email' => $randomEmail]);
        $userTemp->setPassword($randomPassword);

        $this->objectManager->clear();

        return $userTemp;
    }

    public function getRandomPayload(?string $option = null): string
    {
        return sprintf($this->userPayload, $this->faker->email, 'password');
    }
}
