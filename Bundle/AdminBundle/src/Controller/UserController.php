<?php

namespace Umbrella\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use function Symfony\Component\Translation\t;

use Umbrella\AdminBundle\Service\UserManagerInterface;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;
use Umbrella\CoreBundle\Controller\BaseController;

class UserController extends BaseController
{
    /**
     * UserController constructor.
     */
    public function __construct(private UmbrellaAdminConfiguration $config)
    {
    }

    public function index(Request $request)
    {
        $table = $this->createTable($this->config->userTable());
        $table->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getCallbackResponse();
        }

        return $this->render('@UmbrellaAdmin/DataTable/index.html.twig', [
            'table' => $table,
        ]);
    }

    public function edit(UserManagerInterface $manager, Request $request, ?int $id = null)
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
                ->toastSuccess(t('Item updated'));
        }

        return $this->js()
            ->modal('@UmbrellaAdmin/User/edit.html.twig', [
                'form' => $form->createView(),
                'entity' => $entity,
            ]);
    }

    public function delete(UserManagerInterface $manager, int $id)
    {
        $entity = $manager->find($id);
        $this->throwNotFoundExceptionIfNull($entity);
        $manager->delete($entity);

        return $this->js()
            ->closeModal()
            ->reloadTable()
            ->toastSuccess(t('Item deleted'));
    }
}
