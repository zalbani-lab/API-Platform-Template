<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Animation;
use App\Services\Modifiers\OnUpdate\AnimationUpdater;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class UpdaterDispatcher implements DispatcherInterface
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
                $updater = new AnimationUpdater($this->security, $this->manager);
                break;
            default:
                $updater = null;
        }
        if (null !== $updater) {
            $updater->update($object);
        }
    }
}
