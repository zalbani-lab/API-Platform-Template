<?php

declare(strict_types=1);

namespace App\Services;

use Symfony\Component\HttpKernel\Event\TerminateEvent;

interface LogCreationInterface
{
    public function createLog(TerminateEvent $event): void;
}
