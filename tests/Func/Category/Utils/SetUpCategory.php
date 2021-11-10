<?php

declare(strict_types=1);

namespace App\Tests\Func\Category\Utils;

use App\Tests\EntityManager\EntityFactory;
use App\Tests\EntityManager\TestEntityManagerInterface;

trait SetUpCategory
{
    protected object $category;
    protected TestEntityManagerInterface $categoryManager;
    protected TestEntityManagerInterface $userManager;
    protected string $randomPayload;

    protected object $user;
    protected array $userLoginCredential;

    /* This function tearDown interact directly the database via doctrine */
    protected function setUp(): void
    {
        $factory = new EntityFactory($this->getEntityManager(), $this->getSecurityEncoder());
        $this->categoryManager = $factory->create('category');
        $this->category = $this->categoryManager->createOne();
        $this->randomPayload = $this->categoryManager->getRandomPayload();

        $this->userManager = $factory->create('user');

        $userCreationOption = [
            'role' => 'ADMIN',
        ];

        $this->user = $this->userManager->createOne($userCreationOption);
        $this->userLoginCredential = $this->userManager->getLoginInformation($this->user->getEmail(), $this->user->getPassword());
    }
}
