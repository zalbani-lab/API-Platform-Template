<?php

declare(strict_types=1);

namespace App\Services\Emailing\AutoEmail;

use Symfony\Component\HttpKernel\Event\TerminateEvent;

interface AutoEmailInterface
{
    public function send(TerminateEvent $event): void;
}
