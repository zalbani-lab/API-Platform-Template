<?php

declare(strict_types=1);

namespace App\Tests\Func\Email\Utils;

use App\Tests\EntityManager\EntityFactory;
use App\Tests\EntityManager\TestEntityManagerInterface;

trait SetUpEmail
{
    protected object $email;
    protected TestEntityManagerInterface $emailManager;
    protected TestEntityManagerInterface $userManager;
    protected string $randomPayload;

    protected object $user;
    protected array $userLoginCredential;

    /* This function tearDown interact directly the database via doctrine */
    protected function setUp(): void
    {
        $factory = new EntityFactory($this->getEntityManager(), $this->getSecurityEncoder());
        $this->emailManager = $factory->create('email');
        $this->email = $this->emailManager->createOne();
        $this->randomPayload = $this->emailManager->getRandomPayload();

        $userCreationOption = [
            'role' => 'ADMIN',
        ];
        $this->userManager = $factory->create('user');
        $this->user = $this->userManager->createOne($userCreationOption);
        $this->userLoginCredential = $this->userManager->getLoginInformation($this->user->getEmail(), $this->user->getPassword());
    }
}
