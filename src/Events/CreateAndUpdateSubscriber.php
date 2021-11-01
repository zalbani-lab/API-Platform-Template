<?php

declare(strict_types=1);

namespace App\Events;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Services\CreateAndUpdateDispatcher;
use App\Services\DispatcherInterface;
use App\Services\UpdaterDispatcher;
use App\Services\CreatorDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateAndUpdateSubscriber implements EventSubscriberInterface
{
    private DispatcherInterface $updaterDispatcher;
    private DispatcherInterface $creatorDispatcher;
    private DispatcherInterface $createAndUpdateDispatcher;

    protected array $methodUpdate = [
        Request::METHOD_PUT,
        Request::METHOD_PATCH,
    ];

    protected array $methodCreation = [
        Request::METHOD_POST,
    ];

    public function __construct(CreateAndUpdateDispatcher $creatorAndUpdateDispatcher, CreatorDispatcher $creatorDispatcher, UpdaterDispatcher $updaterDispatcher)
    {
        $this->createAndUpdateDispatcher = $creatorAndUpdateDispatcher;
        $this->creatorDispatcher = $creatorDispatcher;
        $this->updaterDispatcher = $updaterDispatcher;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['update', EventPriorities::POST_VALIDATE],
        ];
    }

    public function update(ViewEvent $event): void
    {
        $method = $event->getRequest()->getMethod();

        if (in_array($method, $this->methodUpdate, true) || in_array($method, $this->methodCreation, true)) {
            $object = $event->getControllerResult();
            $this->createAndUpdateDispatcher->dispatch($object);

            if (in_array($method, $this->methodCreation, true)) {
                $this->creatorDispatcher->dispatch($object);
            }
            if (in_array($method, $this->methodUpdate, true)) {
                $this->updaterDispatcher->dispatch($object);
                $object->setUpdatedAt(new \DateTimeImmutable());
            }
        }
    }
}
