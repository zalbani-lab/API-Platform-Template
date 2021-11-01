<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Animation;
use App\Services\Modifiers\OnCreate\AnimationCreation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class CreateAndUpdateDispatcher implements DispatcherInterface
{
//    private Security $security;
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
//        $this->security = $security;
        $this->manager = $manager;
    }

    public function dispatch(object $object): void
    {
        switch ($object) {
            default:
                $creator = null;
        }
        if (null !== $creator) {
            $creator->update($object);
        }
    }
}
