<?php

namespace Umbrella\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use function Symfony\Component\Translation\t;

use Umbrella\AdminBundle\Lib\Controller\AdminController;
use Umbrella\AdminBundle\Service\UserManagerInterface;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;

class UserController extends AdminController
{
    public function __construct(private readonly UmbrellaAdminConfiguration $config)
    {
    }

    public function index(Request $request): Response
    {
        $table = $this->createTable($this->config->userTable());
        $table->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getCallbackResponse();
        }

        return $this->render('@UmbrellaAdmin/datatable.html.twig', [
            'table' => $table,
        ]);
    }

    public function edit(UserManagerInterface $manager, Request $request, ?int $id = null): Response
    {
        if (null === $id) {
            $entity = $manager->create();
        } else {
            $entity = $manager->find($id);
            $this->throwNotFoundExceptionIfNull($entity);
        }

        $form = $this->createForm($this->config->userForm(), $entity, [
            'password_required' => null === $id,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->update($entity);

            return $this->js()
                ->closeModal()
                ->reloadTable()
                ->toastSuccess(t('message.item_updated', [], 'UmbrellaAdmin'));
        }

        return $this->js()
            ->modal('@UmbrellaAdmin/user/edit.html.twig', [
                'form' => $form->createView(),
                'entity' => $entity,
            ]);
    }

    public function delete(UserManagerInterface $manager, int $id): Response
    {
        $entity = $manager->find($id);
        $this->throwNotFoundExceptionIfNull($entity);
        $manager->delete($entity);

        return $this->js()
            ->closeModal()
            ->reloadTable()
            ->toastSuccess(t('message.item_deleted', [], 'UmbrellaAdmin'));
    }
}
