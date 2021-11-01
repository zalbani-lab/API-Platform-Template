<?php

declare(strict_types=1);

namespace App\Services\Emailing\AutoEmail;

use App\Entity\User;
use App\Services\Emailing\EmailSenderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Event\TerminateEvent;

class WelcomeEmails implements AutoEmailInterface
{
    private string $emailSubject = 'Compte cree avec succes';
    private string $emailContext = 'Automatic Email';

    private EmailSenderInterface $mailer;
    private EntityManagerInterface $manager;

    public function __construct(EmailSenderInterface $mailer, EntityManagerInterface $manager)
    {
        $this->mailer = $mailer;
        $this->manager = $manager;
    }

    public function send(TerminateEvent $event): void
    {
        $user = json_decode($event->getResponse()->getContent());
        $user = $this->manager->getRepository(User::class)
            ->findOneById($user->{'id'});
        if (null !== $user && $user instanceof User) {
            $this->mailer->sendOneTemplateEmail($user->getEmail(), $this->emailSubject, 'welcome', [], $this->emailContext);
        }
    }
}
