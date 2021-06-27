<?php

namespace Umbrella\CoreBundle\JsResponse;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class JsResponseViewListener implements EventSubscriberInterface
{
    public function onKernelView(ViewEvent $event): void
    {
        $result = $event->getControllerResult();
        if ($result instanceof JsResponseBuilder) {
            $event->setResponse($result->getResponse());
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['onKernelView', 40],
        ];
    }
}
