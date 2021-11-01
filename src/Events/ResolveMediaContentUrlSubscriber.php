<?php

namespace App\Events;

use ApiPlatform\Core\EventListener\EventPriorities;
use ApiPlatform\Core\Util\RequestAttributesExtractor;
use App\Entity\Media;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Vich\UploaderBundle\Storage\StorageInterface;

final class ResolveMediaContentUrlSubscriber implements EventSubscriberInterface
{
    private StorageInterface $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['onPreSerialize', EventPriorities::PRE_SERIALIZE],
        ];
    }

    public function onPreSerialize(ViewEvent $event): void
    {
        $controllerResult = $event->getControllerResult();
        $request = $event->getRequest();

        if ($controllerResult instanceof Response || !$request->attributes->getBoolean('_api_respond', true)) {
            return;
        }

        if (!($attributes = RequestAttributesExtractor::extractAttributes($request)) || !\is_a($attributes['resource_class'], Media::class, true)) {
            return;
        }

        $medias = $controllerResult;

        if (!is_iterable($medias)) {
            $medias = [$medias];
        }

        foreach ($medias as $media) {
            if (!$media instanceof Media) {
                continue;
            }

            // @todo : This is a temporary fix for test he have to be change
            // The problem is the varibale $_server['http_host'] is undefined when executing tests
            if ('test' === $_SERVER['APP_ENV']) {
                $media->contentUrl = (isset($_SERVER['HTTPS']) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http').'://127.0.0.1:8000'.$this->storage->resolveUri($media, 'file');
            } else {
                $media->contentUrl = (isset($_SERVER['HTTPS']) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].$this->storage->resolveUri($media, 'file');
            }
        }
    }
}
