<?php

namespace Umbrella\AdminBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Contracts\Translation\TranslatorInterface;
use Umbrella\AdminBundle\DataTable\DataTableBuilder;
use Umbrella\AdminBundle\DataTable\DataTableFactory;
use Umbrella\AdminBundle\DataTable\DataTableType;
use Umbrella\AdminBundle\DataTable\DTO\DataTable;
use Umbrella\AdminBundle\JsResponse\JsResponseBuilder;

abstract class AdminController extends AbstractController
{
    public const BAG_TOAST = 'toast';

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            DataTableFactory::class => DataTableFactory::class,
            JsResponseBuilder::class => JsResponseBuilder::class,
            'doctrine' => ManagerRegistry::class,
            'translator' => TranslatorInterface::class,
        ]);
    }

    protected function trans(?string $id, array $parameters = [], string $domain = null, string $locale = null): string
    {
        return $this->container->get('translator')->trans($id, $parameters, $domain, $locale);
    }

    protected function getRepository(string $className, ?string $managerName = null): EntityRepository
    {
        return $this->em($managerName)->getRepository($className);
    }

    protected function em(?string $name = null): EntityManagerInterface
    {
        /** @var EntityManagerInterface $em */
        $em = $this->container->get('doctrine')->getManager($name);

        return $em;
    }

    protected function persistAndFlush(object $elem): void
    {
        $this->em()->persist($elem);
        $this->em()->flush();
    }

    protected function removeAndFlush(object $elem): void
    {
        $this->em()->remove($elem);
        $this->em()->flush();
    }

    protected function findOrNotFound(string $className, $id): ?object
    {
        $e = $this->em()->find($className, $id);
        $this->throwNotFoundExceptionIfNull($e);

        return $e;
    }

    protected function js(): JsResponseBuilder
    {
        return $this->container->get(JsResponseBuilder::class);
    }

    // DataTable Api

    protected function createTable(string $type, array $options = []): DataTable
    {
        return $this->container->get(DataTableFactory::class)->create($type, $options);
    }

    protected function createTableBuilder(array $options = []): DataTableBuilder
    {
        return $this->container->get(DataTableFactory::class)->createBuilder(DataTableType::class, $options);
    }

    // Toast Api

    protected function toast(string $type, $text, $title = null): void
    {
        $this->addFlash(self::BAG_TOAST, [
            'type' => $type,
            'text' => $text instanceof TranslatableMessage ? $text->trans($this->container->get('translator')) : $text,
            'title' => $title instanceof TranslatableMessage ? $title->trans($this->container->get('translator')) : $title,
        ]);
    }

    protected function toastInfo($text, $title = null): void
    {
        $this->toast('info', $text, $title);
    }

    protected function toastSuccess($text, $title = null): void
    {
        $this->toast('success', $text, $title);
    }

    protected function toastWarning($text, $title = null): void
    {
        $this->toast('warning', $text, $title);
    }

    protected function toastError($text, $title = null): void
    {
        $this->toast('error', $text, $title);
    }

    // Exception helper

    protected function throwNotFoundExceptionIfNull($target, string $message = 'Not Found'): void
    {
        if (null === $target) {
            throw $this->createNotFoundException($message);
        }
    }

    protected function throwAccessDeniedExceptionIfFalse($target, string $message = ''): void
    {
        if (false === $target) {
            throw $this->createAccessDeniedException($message);
        }
    }
}
