<?php

namespace Umbrella\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\Translation\t;
use Umbrella\AdminBundle\Services\UserManager;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;

/**
 * @Route("/user")
 */
class UserController extends AdminController
{
    private UmbrellaAdminConfiguration $config;

    /**
     * UserController constructor.
     */
    public function __construct(UmbrellaAdminConfiguration $config)
    {
        $this->config = $config;
    }

    /**
     * @Route("")
     */
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

    /**
     * @Route("/edit/{id}", requirements={"id": "\d+"})
     */
    public function edit(UserManager $manager, Request $request, ?int $id = null)
    {
        if (null === $id) {
            $entity = $manager->createUser();
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

            return $this->jsResponseBuilder()
                ->closeModal()
                ->reloadTable()
                ->toastSuccess(t('message.entity_updated'));
        }

        return $this->jsResponseBuilder()
            ->modal('@UmbrellaAdmin/User/edit.html.twig', [
                'form' => $form->createView(),
                'title' => null !== $id ? $this->trans('action.edit_user') : $this->trans('action.add_user'),
                'entity' => $entity,
            ]);
    }

    /**
     * @Route("/delete/{id}", requirements={"id": "\d+"})
     */
    public function delete(UserManager $manager, Request $request, int $id)
    {
        $entity = $manager->find($id);
        $this->throwNotFoundExceptionIfNull($entity);
        $manager->remove($entity);

        return $this->jsResponseBuilder()
            ->closeModal()
            ->reloadTable()
            ->toastSuccess(t('message.entity_deleted'));
    }
}
