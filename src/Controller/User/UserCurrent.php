<?php

// @todo: Override associate documentation

declare(strict_types=1);

namespace App\Controller\User;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class UserCurrent
{
    private ?UserInterface $user;

    public function __construct(Security $security)
    {
        $this->user = $security->getUser();
    }

    public function __invoke(): UserInterface
    {
        return $this->user;
    }
}
