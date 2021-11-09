<?php

declare(strict_types=1);

namespace App\Services\Modifiers\OnCreate;

use App\Entity\Animation;
use App\Services\Modifiers\ModifierInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class AnimationCreation implements ModifierInterface
{
    private ?UserInterface $user;

    public function __construct(Security $security)
    {
        $this->user = $security->getUser();
    }

    public function update(object $object): void
    {
        if ($object instanceof Animation) {
            $animation = $object;
            $this->addAuthor($animation);
        }
    }
    private function addAuthor(Animation $animation): void
    {
        $animation->setAuthor($this->user);
    }
}
