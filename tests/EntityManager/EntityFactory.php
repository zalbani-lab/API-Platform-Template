<?php

declare(strict_types=1);

namespace App\Tests\EntityManager;

use App\Tests\EntityManager\Entity\Animation\AnimationManager;
use App\Tests\EntityManager\Entity\EmailManager;
use App\Tests\EntityManager\Entity\LogManager;
use App\Tests\EntityManager\Entity\Media\MediaManager;
use App\Tests\EntityManager\Entity\CategoryManager;
use App\Tests\EntityManager\Entity\User\UserManager;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class EntityFactory
{
    private ObjectManager $objectManager;
    private UserPasswordEncoder $passwordEncoder;

    public function __construct(ObjectManager $objectManager, UserPasswordEncoder $passwordEncoder)
    {
        $this->objectManager = $objectManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function create($type): TestEntityManagerInterface
    {
        switch ($type) {
            case 'user':
                $response = new UserManager($this->objectManager, $this->passwordEncoder);
                break;
            case 'animation':
                $response = new AnimationManager($this->objectManager);
                break;
            case 'category':
                $response = new CategoryManager($this->objectManager);
                break;
            case 'log':
                $response = new LogManager($this->objectManager);
                break;
            case 'media':
                $response = new MediaManager($this->objectManager);
                break;
            case 'email':
                $response = new EmailManager($this->objectManager);
                break;
            default:
                $response = null;
        }

        return $response;
    }
}
