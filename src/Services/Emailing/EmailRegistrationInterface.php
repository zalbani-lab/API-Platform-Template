<?php

declare(strict_types=1);

namespace App\Services\Emailing;

interface EmailRegistrationInterface
{
    public function registerRawEmail(array $recipients, string $replyAddress, string $subject, string $content, string $logContext = ''): void;
    public function registerTemplateEmail(array $recipients, string $replyAddress, string $subject, string $templateName, array $templateContext, string $logContext = ''): void;
}
