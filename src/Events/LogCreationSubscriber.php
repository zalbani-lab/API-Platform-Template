<?php

declare(strict_types=1);

namespace App\Events;

use App\Services\LogCreationInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LogCreationSubscriber implements EventSubscriberInterface
{
    protected array $methodToInteractWith = [
        Request::METHOD_PUT,
        Request::METHOD_PATCH,
        Request::METHOD_POST,
        Request::METHOD_DELETE,
    ];
    protected array $excludeUri = [
        '/api/login',
    ];
    private LogCreationInterface $logCreation;

    public function __construct(LogCreationInterface $logCreation)
    {
        $this->logCreation = $logCreation;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::TERMINATE => ['log'],
        ];
    }

    public function log(TerminateEvent $event): void
    {
        $validMethod = $this->isValidMethod($event->getRequest()->getMethod());
        $validUri = $this->isValidUri($event->getRequest()->getPathInfo());
        $haveToBeLogged = $this->haveToBeLogged($event->getRequest()->query->get('log'));

        if ($validMethod && $validUri && $haveToBeLogged) {
            $this->logCreation->createLog($event);
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
        foreach ($this->excludeUri as $excludeUri) {
            if (false !== strpos($currentUri, $excludeUri)) {
                return false;
            }
        }

        return true;
    }

    private function haveToBeLogged(?string $parameterLog): bool
    {
        switch ($parameterLog) {
            case '0':
            case 'false':
                return false;
            default:
                return true;
        }
    }
}
