<?php

declare(strict_types=1);

namespace App\Services\Emailing;

interface EmailSenderInterface
{
    public function sendTemplateEmail(array $recipients, ?string $replyAddress, string $subject, string $templateName, array $templateContext = [], string $logContext = ''): void;

    public function sendNormalEmail(array $recipients, ?string $replyAddress, string $subject, string $content, string $logContext = ''): void;

    // The only difference with function above is the $recipient type
    // In the future version of php you can declare multiple type for a single variable
    // Multi type implementation in php : https://php.watch/versions/8.0/union-types
    // @todo: Si il y a une migration vers php 8.x il est preferable de refactor ces fonctions afin de la rendre moins redondante, regarder la documentation ci-dessus
    public function sendOneTemplateEmail(string $recipient, string $subject, string $templateName, array $templateContext = [], string $logContext = ''): void;

    public function sendOneNormalEmail(string $recipient, string $subject, string $content, string $logContext = ''): void;
}
