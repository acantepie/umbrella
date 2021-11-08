<?php

namespace Umbrella\CoreBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Contracts\Translation\TranslatorInterface;
use Umbrella\CoreBundle\DataTable\DataTableBuilder;
use Umbrella\CoreBundle\DataTable\DataTableFactory;
use Umbrella\CoreBundle\DataTable\DataTableType;
use Umbrella\CoreBundle\DataTable\DTO\DataTable;
use Umbrella\CoreBundle\JsResponse\JsResponseBuilder;

abstract class BaseController extends AbstractController
{
    public const BAG_TOAST = 'toast';

    public static function getSubscribedServices()
    {
        return parent::getSubscribedServices() + [
                DataTableFactory::class => DataTableFactory::class,
                JsResponseBuilder::class => JsResponseBuilder::class,
                'translator' => TranslatorInterface::class,
            ];
    }

    protected function trans(?string $id, array $parameters = [], string $domain = null, string $locale = null): string
    {
        return $this->get('translator')->trans($id, $parameters, $domain, $locale);
    }

    protected function getRepository(string $className, ?string $managerName = null): EntityRepository
    {
        /** @var EntityRepository $repo */
        $repo = $this->em($managerName)->getRepository($className);

        return $repo;
    }

    protected function em(?string $name = null): EntityManagerInterface
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager($name);

        return $em;
    }

    protected function persistAndFlush($elem): void
    {
        $this->em()->persist($elem);
        $this->em()->flush();
    }

    protected function removeAndFlush($elem): void
    {
        $this->em()->remove($elem);
        $this->em()->flush();
    }

    /**
     * @return object|null
     */
    protected function findOrNotFound(string $className, $id)
    {
        $e = $this->em()->find($className, $id);
        $this->throwNotFoundExceptionIfNull($e);

        return $e;
    }

    protected function js(): JsResponseBuilder
    {
        return $this->get(JsResponseBuilder::class);
    }

    // DataTable Api

    protected function createTable(string $type, array $options = []): DataTable
    {
        return $this->get(DataTableFactory::class)->create($type, $options);
    }

    protected function createTableBuilder(array $options = []): DataTableBuilder
    {
        return $this->get(DataTableFactory::class)->createBuilder(DataTableType::class, $options);
    }

    // Toast Api

    protected function toast(string $type, $text, $title = null): void
    {
        $this->addFlash(self::BAG_TOAST, [
            'type' => $type,
            'text' => $text instanceof TranslatableMessage ? $text->trans($this->get('translator')) : $text,
            'title' => $title instanceof TranslatableMessage ? $title->trans($this->get('translator')) : $title,
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

}
