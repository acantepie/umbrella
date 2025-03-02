<?php

namespace Umbrella\CoreBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Contracts\Translation\TranslatorInterface;
use Umbrella\CoreBundle\DataTable\DataTableBuilder;
use Umbrella\CoreBundle\DataTable\DataTableFactory;
use Umbrella\CoreBundle\DataTable\DataTableType;
use Umbrella\CoreBundle\DataTable\DTO\DataTable;
use Umbrella\CoreBundle\JsResponse\JsResponse;
use Umbrella\CoreBundle\JsResponse\JsResponseFactory;

abstract class BaseController extends AbstractController
{
    public const BAG_TOAST = 'toast';

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            DataTableFactory::class => DataTableFactory::class,
            JsResponseFactory::class => JsResponseFactory::class,
            'doctrine' => ManagerRegistry::class,
            'translator' => TranslatorInterface::class,
        ]);
    }

    protected function trans(?string $id, array $parameters = [], ?string $domain = null, ?string $locale = null): string
    {
        return $this->container->get('translator')->trans($id, $parameters, $domain, $locale);
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $className
     *
     * @return EntityRepository<T>
     */
    protected function getRepository(string $className, ?string $managerName = null)
    {
        return $this->container->get('doctrine')->getRepository($className, $managerName);
    }

    protected function em(?string $name = null): EntityManagerInterface
    {
        return $this->container->get('doctrine')->getManager($name);
    }

    protected function persistAndFlush(object $elem, ?string $managerName = null): void
    {
        $em = $this->em($managerName);

        $em->persist($elem);
        $em->flush();
    }

    protected function removeAndFlush(object $elem, ?string $managerName = null): void
    {
        $em = $this->em($managerName);

        $em->remove($elem);
        $em->flush();
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $className
     *
     * @return T
     */
    protected function findOrNotFound(string $className, mixed $id, ?string $managerName = null): object
    {
        $em = $this->em($managerName);

        $e = $em->find($className, $id);
        $this->throwNotFoundExceptionIfNull($e);

        return $e;
    }

    protected function js(): JsResponse
    {
        return $this->container->get(JsResponseFactory::class)->create();
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

    protected function toast(string $type, TranslatableMessage|string $text, TranslatableMessage|string|null $title = null): void
    {
        $this->addFlash(self::BAG_TOAST, [
            'type' => $type,
            'text' => $text instanceof TranslatableMessage ? $text->trans($this->container->get('translator')) : $text,
            'title' => $title instanceof TranslatableMessage ? $title->trans($this->container->get('translator')) : $title,
        ]);
    }

    protected function toastInfo(TranslatableMessage|string $text, TranslatableMessage|string|null $title = null): void
    {
        $this->toast('info', $text, $title);
    }

    protected function toastSuccess(TranslatableMessage|string $text, TranslatableMessage|string|null $title = null, bool $safeHtml = true): void
    {
        $this->toast('success', $text, $title);
    }

    protected function toastWarning(TranslatableMessage|string $text, TranslatableMessage|string|null $title = null, bool $safeHtml = true): void
    {
        $this->toast('warning', $text, $title);
    }

    protected function toastError(TranslatableMessage|string $text, TranslatableMessage|string|null $title = null): void
    {
        $this->toast('error', $text, $title);
    }

    // Exception helper

    protected function throwNotFoundExceptionIfNull(mixed $target, string $message = 'Not Found'): void
    {
        if (null === $target) {
            throw $this->createNotFoundException($message);
        }
    }

    protected function throwAccessDeniedExceptionIfFalse(mixed $target, string $message = ''): void
    {
        if (false === $target) {
            throw $this->createAccessDeniedException($message);
        }
    }
}
