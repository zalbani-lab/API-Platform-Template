<?php

declare(strict_types=1);

namespace App\Tests\Func\Animation\Utils;

use App\Tests\EntityManager\EntityFactory;
use App\Tests\EntityManager\TestEntityManagerInterface;

trait SetUpAnimation
{
    protected object $animation;
    protected TestEntityManagerInterface $animationManager;
    protected TestEntityManagerInterface $userManager;
    protected string $randomPayload;

    protected object $author;
    protected array $authorLoginCredential;

    /* This function tearDown interact directly the database via doctrine */
    protected function setUp(): void
    {
        $factory = new EntityFactory($this->getEntityManager(), $this->getSecurityEncoder());
        $this->animationManager = $factory->create('animation');
        $this->animation = $this->animationManager->createOne();
        $this->randomPayload = $this->animationManager->getRandomPayload();

        $this->userManager = $factory->create('user');
        $this->author = $this->userManager->createOne();
        $this->authorLoginCredential = $this->userManager->getLoginInformation($this->author->getEmail(), $this->author->getPassword());
    }
}
