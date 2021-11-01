<?php

declare(strict_types=1);

namespace App\Events;

use App\Services\ResponseBuilderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionListener implements EventSubscriberInterface
{
    private ResponseBuilderInterface $responseBuilder;

    public function __construct(ResponseBuilderInterface $responseBuilder)
    {
        $this->responseBuilder = $responseBuilder;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [['processException', 0]],
        ];
    }

    public function processException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $code = Response::HTTP_BAD_REQUEST;
        $message = $exception->getMessage();

        if (false !== strpos($exception->getMessage(), 'ERROR_CODE')) {
            $explodeTable = explode(' % ', $exception->getMessage());
            $code = intval($explodeTable[1]);
            $message = ltrim($explodeTable[2]);
        }
        $event->setResponse($this->responseBuilder->getResponse($code, $message));
    }
}
