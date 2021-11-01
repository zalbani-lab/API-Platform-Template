<?php

declare(strict_types=1);

namespace App\Tests\Func\Media\Utils;

use App\Tests\EntityManager\EntityFactory;
use App\Tests\EntityManager\TestEntityManagerInterface;

trait SetUpMedia
{
    protected object $media;
    protected TestEntityManagerInterface $mediaManager;
    protected TestEntityManagerInterface $userManager;
    protected string $randomPayload;

    protected object $user;
    protected array $userLoginCredential;

    /* This function tearDown interact directly the database via doctrine */
    protected function setUp(): void
    {
        $factory = new EntityFactory($this->getEntityManager(), $this->getSecurityEncoder());
        $this->mediaManager = $factory->create('media');
        $this->media = $this->mediaManager->createOne();
//        $this->randomPayload = $this->mediaManager->getRandomPayload();

        $userCreationOption = [
            'role' => 'ADMIN',
        ];
        $this->userManager = $factory->create('user');
        $this->user = $this->userManager->createOne($userCreationOption);
        $this->userLoginCredential = $this->userManager->getLoginInformation($this->user->getEmail(), $this->user->getPassword());
    }
}
