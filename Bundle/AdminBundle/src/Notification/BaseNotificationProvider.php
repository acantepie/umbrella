<?php

namespace Umbrella\AdminBundle\Notification;

use Knp\Bundle\TimeBundle\DateTimeFormatter;
use Twig\Environment;
use Umbrella\AdminBundle\Entity\BaseNotification;

abstract class BaseNotificationProvider implements NotificationProviderInterface
{
    protected ?Environment $twig = null;
    protected ?DateTimeFormatter $timeFormatter = null;

    public function setTwig(Environment $twig): void
    {
        $this->twig = $twig;
    }

    public function setTimeFormatter(DateTimeFormatter $timeFormatter): void
    {
        $this->timeFormatter = $timeFormatter;
    }

    /**
     * {@inheritDoc}
     */
    public function render(BaseNotification $notification): string
    {
        if (null === $this->timeFormatter) {
            $date = $notification->createdAt->format('d/m/Y H:i');
        } else {
            $date = $this->timeFormatter->formatDiff($notification->createdAt, new \DateTime());
        }

        $data = [
            'icon_color' => $notification->iconColor,
            'icon' => $notification->icon,
            'title' => $notification->title,
            'text' => $notification->text,
            'url' => $notification->url,
            'date' => $date
        ];

        return $this->twig->render('@UmbrellaAdmin/Notification/notification.html.twig', $data);
    }
}
