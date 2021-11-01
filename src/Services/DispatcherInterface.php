<?php

declare(strict_types=1);

namespace App\Services;

interface DispatcherInterface
{
    public function dispatch(object $object): void;
}
