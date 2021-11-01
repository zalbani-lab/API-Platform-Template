<?php

declare(strict_types=1);

namespace App\Services\Emailing;

use App\Services\Emailing\AutoEmail\WelcomeEmails;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\TerminateEvent;

class AutoEmailDispatcher implements AutoEmailDispatcherInterface
{
    private EmailSenderInterface $mailer;
    private EntityManagerInterface $manager;

    public function __construct(EmailSenderInterface $mailer, EntityManagerInterface $manager)
    {
        $this->mailer = $mailer;
        $this->manager = $manager;
    }

    public function dispatch(string $method, int $statusCode, string $uri, TerminateEvent $event): void
    {
        $email = null;
        switch ($uri) {
            case '/api/users':
                if (Request::METHOD_POST === $method && Response::HTTP_CREATED === $statusCode) {
                    $email = new WelcomeEmails($this->mailer, $this->manager);
                }
                break;
            default:
                $email = null;
        }
        if (null !== $email) {
            $email->send($event);
        }
    }
}
