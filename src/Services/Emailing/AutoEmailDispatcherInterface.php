<?php

declare(strict_types=1);

namespace App\Services\Emailing;

use Symfony\Component\HttpKernel\Event\TerminateEvent;

interface AutoEmailDispatcherInterface
{
    public function dispatch(string $method, int $statusCode, string $uri, TerminateEvent $event): void;
}
