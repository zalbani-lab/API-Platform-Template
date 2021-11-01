<?php

declare(strict_types=1);

namespace App\Services\Emailing;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailSender implements EmailSenderInterface
{
    private MailerInterface $mailer;
    private EmailRegistrationInterface $emailRegisterer;

    public function __construct(MailerInterface $mailer, EmailRegistrationInterface $emailRegisterer)
    {
        $this->mailer = $mailer;
        $this->emailRegisterer = $emailRegisterer;
    }

    public function sendRawEmail(array $recipients, ?string $replyAddress, string $subject, string $content, string $logContext = ''): void
    {
        $replyAddress = $this->getReplyAddress($replyAddress);

        $email = (new TemplatedEmail())
            ->from('random@mail.fr')
            ->to(...$recipients)
            ->replyTo($replyAddress)
            ->subject($subject)
            ->html($content)
        ;

        $this->emailRegisterer->registerRawEmail($recipients, $replyAddress, $subject, $content, $logContext);

        $this->send($email);
    }

    public function sendNormalEmail(array $recipients, ?string $replyAddress, string $subject, string $content, string $logContext = ''): void
    {
        $templateContext = [
            'content' => $content,
        ];

        $this->sendTemplateEmail($recipients, $replyAddress, $subject, 'default', $templateContext, $logContext);
    }

    public function sendTemplateEmail(array $recipients, ?string $replyAddress, string $subject, string $templateName, array $templateContext = [], string $logContext = ''): void
    {
        $replyAddress = $this->getReplyAddress($replyAddress);
        $templateUri = $this->getTemplateUri($templateName);

        $email = (new TemplatedEmail())
            ->from('random@mail.fr')
            ->to(...$recipients)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            ->replyTo($replyAddress)
            //->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
            ->htmlTemplate($templateUri)
            ->context($templateContext)
        ;

        $this->emailRegisterer->registerTemplaEmail($recipients, $replyAddress, $subject, $templateName, $templateContext, $logContext);

        $this->send($email);
    }

    public function sendOneNormalEmail(string $recipient, string $subject, string $content, string $logContext = ''): void
    {
        $recipients = $this->transformStringIntoArray($recipient);
        $this->sendNormalEmail($recipients, null, $subject, $content, $logContext);
    }

    public function sendOneTemplateEmail(string $recipient, string $subject, string $templateName, array $templateContext = [], string $logContext = ''): void
    {
        $recipients = $this->transformStringIntoArray($recipient);
        $this->sendTemplateEmail($recipients, null, $subject, $templateName, $templateContext, $logContext);
    }

    private function send(Email $email): void
    {
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            var_dump($e);
        }
    }

    private function transformStringIntoArray(string $string): array
    {
        return explode(';', $string);
    }

    private function getTemplateUri(?string $templateNameRequest): string
    {
        $templateName = 'default';
        if ($templateNameRequest !== null) {
            $templateName = $templateNameRequest;
        }
        return '/emails/'.$templateName.'.html.twig';
    }

    private function getReplyAddress(?string $replyAddressRequest): string
    {
        $replyAddress = 'no-reply@bm-lyon.fr';
        if ($replyAddressRequest !== null) {
            $replyAddress = $replyAddressRequest;
        }
        return $replyAddress;
    }
}
