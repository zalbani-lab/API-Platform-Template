<?php

declare(strict_types=1);

namespace App\Events;

use App\Services\Emailing\AutoEmailDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AutoEmailSenderSubscriber implements EventSubscriberInterface
{
    private AutoEmailDispatcherInterface $emailDispatcher;

    public function __construct(AutoEmailDispatcherInterface $emailDispatcher)
    {
        $this->emailDispatcher = $emailDispatcher;
    }

    // @todo: Faire un tableau associatif. Pour le moment ce n'est pas derangeant mais si on commence a avoir beaucoup de mail automatique il ne faudrait pas appeller systematiquement l'email dispatcher si celui-ci est vouer a ne pas envoyer de mail
    protected array $methodToInteractWith = [
        Request::METHOD_POST,
    ];
    protected array $uriNeeded = [
        '/api/users',
    ];

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::TERMINATE => ['callEmailDispatcher'],
        ];
    }

    public function callEmailDispatcher(TerminateEvent $event): void
    {
        $statusCode = $event->getResponse()->getStatusCode();
        $method = $event->getRequest()->getMethod();
        $validUri = $event->getRequest()->getPathInfo();
        $emailQueryOption = $event->getRequest()->query->get('email');

        if ($this->isValidMethod($method) && $this->isValidUri($validUri) && $this->haveToBeSendAndLoged($emailQueryOption)) {
            $this->emailDispatcher->dispatch($method, $statusCode, $validUri, $event);
        }
    }

    private function isValidMethod($method): bool
    {
        if (in_array($method, $this->methodToInteractWith, true)) {
            return true;
        } else {
            return false;
        }
    }

    private function isValidUri(string $currentUri): bool
    {
        foreach ($this->uriNeeded as $uriNeeded) {
            if (false !== strpos($currentUri, $uriNeeded)) {
                return true;
            }
        }

        return false;
    }

    private function haveToBeSendAndLoged(?string $parameterEmail): bool
    {
        switch ($parameterEmail) {
            case '0':
            case 'false':
                return false;
            default:
                return true;
        }
    }
}
