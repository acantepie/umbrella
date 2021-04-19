<?php

namespace Umbrella\CoreBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Contracts\Translation\TranslatorInterface;
use Umbrella\CoreBundle\Component\DataTable\DataTableFactory;
use Umbrella\CoreBundle\Component\DataTable\DTO\DataTable;
use Umbrella\CoreBundle\Component\JsResponse\JsResponseBuilder;
use Umbrella\CoreBundle\Component\Toast\Toast;

/**
 * Class BaseController.
 */
abstract class BaseController extends AbstractController
{
    const BAG_TOAST = 'toast';

    public static function getSubscribedServices()
    {
        return parent::getSubscribedServices() + [
                'datatable.factory' => DataTableFactory::class,
                'jsresponse.builder' => JsResponseBuilder::class,
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

    protected function jsResponseBuilder(): JsResponseBuilder
    {
        /** @phpstan-ignore-next-line */
        return $this->get('jsresponse.builder');
    }

    // DataTable Api

    protected function createTable(string $type, array $options = []): DataTable
    {
        /** @phpstan-ignore-next-line */
        return $this->get('datatable.factory')->create($type, $options);
    }

    // Toast Api

    protected function toast(Toast $toast): Toast
    {
        /** @var Session $session */
        $session = $this->get('session');
        $session->getFlashBag()->add(self::BAG_TOAST, $toast);

        return $toast;
    }

    public function toastInfo($text, $title = null, bool $safeHtml = true): Toast
    {
        return $this->toast(Toast::createInfo($text, $title, $safeHtml));
    }

    public function toastSuccess($text, $title = null, bool $safeHtml = true): Toast
    {
        return $this->toast(Toast::createSuccess($text, $title, $safeHtml));
    }

    public function toastWarning($text, $title = null, bool $safeHtml = true): Toast
    {
        return $this->toast(Toast::createWarning($text, $title, $safeHtml));
    }

    public function toastError($text, $title = null, bool $safeHtml = true): Toast
    {
        return $this->toast(Toast::createError($text, $title, $safeHtml));
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
