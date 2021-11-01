<?php

declare(strict_types=1);

namespace App\Services\Emailing;

use App\Entity\Email;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\Mapping\MappingException;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class EmailRegistration implements EmailRegistrationInterface
{
    private array $templateEmailWithSensitiveData = [
        'resetPassword',
    ];

    private string $confidentialMessage = 'Le contenu de cet email est massquer car il contient des informations sensible';

    private EntityManagerInterface $entityManager;
    private Security $security;
    private Environment $twig;

    public function __construct(EntityManagerInterface $entityManager, Security $security, Environment $twig)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->twig = $twig;
    }

    public function registerRawEmail(array $recipients, string $replyAddress, string $subject, string $content, string $logContext = ''): void
    {
        $author = $this->getConnectedUserId();

        $tempEmail = new Email();
        $tempEmail->setAuthor($author)
            ->setRecipient($this->transformRecipientToString($recipients))
            ->setReplyAddress($replyAddress)
            ->setSubject($subject)
            ->setTemplate('no template')
            ->setContent($content)
            ->setContext($logContext);
        try {
            $this->entityManager->persist($tempEmail);
            $this->entityManager->flush();
            $this->entityManager->clear();
        } catch (ORMException | MappingException $e) {
            // @todo: Write something here
            // var_dump($e);
        }
    }

    public function registerTemplateEmail(array $recipients, string $replyAddress, string $subject, string $templateName, array $templateContext, string $logContext = ''): void
    {
        $author = $this->getConnectedUserId();
        $emailContent = $this->getEmailContent($templateName, $templateContext);

        $tempEmail = new Email();
        $tempEmail->setAuthor($author)
            ->setRecipient($this->transformRecipientToString($recipients))
            ->setReplyAddress($replyAddress)
            ->setSubject($subject)
            ->setTemplate($templateName)
            ->setContent($emailContent)
            ->setContext($logContext);
        try {
            $this->entityManager->persist($tempEmail);
            $this->entityManager->flush();
            $this->entityManager->clear();
        } catch (ORMException | MappingException $e) {
            // @todo: Write something here
            // var_dump($e);
        }
    }

    private function getConnectedUserId(): ?int
    {
        if ($this->security->getUser()) {
            return $this->security->getUser()->getId();
        } else {
            return null;
        }
    }

    private function getEmailContent($templateName, $templateContext): string
    {
        if (!$this->isCompromiseContent($templateName)) {
            $templateUri = '/emails/'.$templateName.'.html.twig';
            try {
                return $this->twig->render($templateUri, $templateContext);
            } catch (LoaderError | RuntimeError | SyntaxError $e) {
                return $e.__toString();
            }
        } else {
            return $this->confidentialMessage;
        }
    }

    private function isCompromiseContent($templateName): bool
    {
        if (in_array($templateName, $this->templateEmailWithSensitiveData, true)) {
            return true;
        } else {
            return false;
        }
    }

    private function transformRecipientToString($recipients): string
    {
        return implode(' ; ', $recipients);
    }
}
