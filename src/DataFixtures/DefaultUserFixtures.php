<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DefaultUserFixtures extends Fixture
{
    const DEFAULT_USER = ['email' => 'user@mail.com', 'password' => 'password'];
    const DEFAULT_ADMIN = ['email' => 'admin@mail.com', 'password' => 'password', 'roles' => ['ROLE_ADMIN']];
    private UserPasswordEncoderInterface $encoder;
    private ObjectManager $manager;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->createDefaultUsers();
        $manager->flush();
    }

    private function createDefaultUsers(): void
    {
        $defaultUser = new User();
        $defaultUser->setEmail(self::DEFAULT_USER['email'])
            ->setPassword($this->hashPassword($defaultUser, self::DEFAULT_USER['password']));
        $this->manager->persist($defaultUser);

        $defaultAdmin = new User();
        $defaultAdmin->setEmail(self::DEFAULT_ADMIN['email'])
            ->setPassword($this->hashPassword($defaultUser, self::DEFAULT_ADMIN['password']))
            ->setRoles(self::DEFAULT_ADMIN['roles']);
        $this->manager->persist($defaultAdmin);
    }

    private function hashPassword(User $user, string $password): string
    {
        return $this->encoder->encodePassword($user, $password);
    }
}
