<?php

namespace Umbrella\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\Translation\t;
use Umbrella\AdminBundle\Services\UserManager;

/**
 * Class UserController.
 *
 * @Route("/user")
 */
class UserController extends AdminController
{
    /**
     * @Route("")
     */
    public function index(Request $request)
    {
        $table = $this->createTable($this->getParameter('umbrella_admin.user.table'));
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
     *
     * @param mixed|null $id
     */
    public function edit(UserManager $manager, Request $request, $id = null)
    {
        if (null === $id) {
            $entity = $manager->createUser();
        } else {
            $entity = $manager->find($id);
            $this->throwNotFoundExceptionIfNull($entity);
        }

        $form = $this->createForm($this->getParameter('umbrella_admin.user.form'), $entity, [
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
    public function delete(UserManager $manager, Request $request, $id)
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
