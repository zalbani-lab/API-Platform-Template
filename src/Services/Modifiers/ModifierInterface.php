<?php

declare(strict_types=1);

namespace App\Services\Modifiers;

interface ModifierInterface
{
    public function update(object $object): void;
}
