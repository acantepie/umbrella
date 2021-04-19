<?php

namespace Umbrella\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\Translation\t;
use Umbrella\AdminBundle\Services\UserGroupManager;

/**
 * Class UserGroupController.
 *
 * @Route("/usergroup")
 */
class UserGroupController extends AdminController
{
    /**
     * @Route("")
     */
    public function indexAction(Request $request)
    {
        $table = $this->createTable($this->getParameter('umbrella_admin.user_group.table'));
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
    public function editAction(UserGroupManager $manager, Request $request, $id = null)
    {
        if (null === $id) {
            $entity = $manager->createGroup();
        } else {
            $entity = $manager->find($id);
            $this->throwNotFoundExceptionIfNull($entity);
        }

        $form = $this->createForm($this->getParameter('umbrella_admin.user_group.form'), $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->update($entity);

            return $this->jsResponseBuilder()
                ->closeModal()
                ->reloadTable()
                ->toastSuccess(t('message.entity_updated'));
        }

        return $this->jsResponseBuilder()
            ->openModalView('@UmbrellaAdmin/UserGroup/edit.html.twig', [
                'form' => $form->createView(),
                'title' => $entity->id ? $this->trans('action.edit_group') : $this->trans('action.add_group'),
                'entity' => $entity,
            ]);
    }

    /**
     * @Route("/delete/{id}", requirements={"id": "\d+"})
     */
    public function deleteAction(UserGroupManager $manager, Request $request, $id)
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
