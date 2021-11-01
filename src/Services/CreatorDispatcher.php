<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Animation;
use App\Entity\Type;
use App\Services\Modifiers\OnCreate\AnimationCreation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class CreatorDispatcher implements DispatcherInterface
{
    private Security $security;
    private EntityManagerInterface $manager;

    public function __construct(Security $security, EntityManagerInterface $manager)
    {
        $this->security = $security;
        $this->manager = $manager;
    }

    public function dispatch(object $object): void
    {
        switch ($object) {
            case $object instanceof Animation:
                $creator = new AnimationCreation($this->security, $this->manager);
                break;
            default:
                $creator = null;
        }
        if (null !== $creator) {
            $creator->update($object);
        }
    }
}
