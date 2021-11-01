<?php

declare(strict_types=1);

namespace App\Tests\Func\User\Utils;

use App\Tests\EntityManager\EntityFactory;
use App\Tests\EntityManager\TestEntityManagerInterface;

trait SetUpUser
{
    protected object $user;
    protected object $userAdmin;
    protected TestEntityManagerInterface $userManager;
    protected string $randomPayload;
    protected array $userLoginCredential;
    protected array $userAdminLoginCredential;

    /* This function tearDown interact directly the database via doctrine */
    protected function setUp(): void
    {
        $factory = new EntityFactory($this->getEntityManager(), $this->getSecurityEncoder());
        $this->userManager = $factory->create('user');
        $this->randomPayload = $this->userManager->getRandomPayload();

        $this->user = $this->userManager->createOne();
        $this->userLoginCredential = $this->userManager->getLoginInformation($this->user->getEmail(), $this->user->getPassword());

        $userCreationOption = [
            'role' => 'ADMIN',
        ];
        $this->userAdmin = $this->userManager->createOne($userCreationOption);
        $this->userAdminLoginCredential = $this->userManager->getLoginInformation($this->userAdmin->getEmail(), $this->userAdmin->getPassword());
    }
}
