<?php

declare(strict_types=1);

namespace App\Services;

use Symfony\Component\HttpFoundation\Response;

interface ResponseBuilderInterface
{
    public function getResponse(int $code, string $message): Response;
}
