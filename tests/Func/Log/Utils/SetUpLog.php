<?php

declare(strict_types=1);

namespace App\Tests\Func\Log\Utils;

use App\Tests\EntityManager\EntityFactory;
use App\Tests\EntityManager\TestEntityManagerInterface;

trait SetUpLog
{
    protected object $log;
    protected object $user;
    protected TestEntityManagerInterface $logManager;
    protected TestEntityManagerInterface $userManager;

    protected array $userAdminCredential;

    /* This function tearDown interact directly the database via doctrine */
    protected function setUp(): void
    {
        $factory = new EntityFactory($this->getEntityManager(), $this->getSecurityEncoder());
        $this->logManager = $factory->create('log');
        $this->log = $this->logManager->createOne();

        $userCreationOption = [
            'role' => 'ADMIN',
        ];
        $this->userManager = $factory->create('user');
        $this->user = $this->userManager->createOne($userCreationOption);
        $this->userAdminCredential = $this->userManager->getLoginInformation($this->user->getEmail(), $this->user->getPassword());
    }
}
