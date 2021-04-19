<?php

namespace Umbrella\AdminBundle\Notification;

use Doctrine\ORM\EntityManagerInterface;
use Umbrella\AdminBundle\Entity\BaseNotification;
use Umbrella\AdminBundle\Notification\Provider\NotificationProviderInterface;
use Umbrella\AdminBundle\Notification\Renderer\NotificationRendererInterface;
use Umbrella\AdminBundle\Notification\Renderer\NotificationView;

class NotificationManager
{
    protected EntityManagerInterface $em;

    protected ?NotificationProviderInterface $_provider = null;

    protected ?NotificationRendererInterface $_renderer = null;

    /**
     * NotificationManager constructor.
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function registerProvider(NotificationProviderInterface $provider)
    {
        $this->_provider = $provider;
    }

    public function registerRenderer(NotificationRendererInterface $renderer)
    {
        $this->_renderer = $renderer;
    }

    /**
     * @param object $user
     *
     * @return iterable|BaseNotification[]
     *
     * @throws NotificationException
     */
    public function findByUser($user): iterable
    {
        if (null === $this->_provider) {
            throw new NotificationException('No provider configured for notification.');
        }

        return $this->_provider->findByUser($user);
    }

    /**
     * Send a notification (i.e. persist it)
     */
    public function send(BaseNotification $notification)
    {
        $this->em->persist($notification);
        $this->em->flush();
    }

    public function remove(BaseNotification $notification)
    {
        $this->em->remove($notification);
        $this->em->flush();
    }

    /**
     * @throws NotificationException
     */
    public function view(BaseNotification $notification): NotificationView
    {
        if (null === $this->_renderer) {
            throw new NotificationException('No renderer configured for notification.');
        }

        return $this->_renderer->render($notification);
    }

    /**
     * @throws NotificationException
     */
    public function emptyView(): NotificationView
    {
        if (null === $this->_renderer) {
            throw new NotificationException('No renderer configured for notification.');
        }

        return $this->_renderer->renderEmpty();
    }
}
