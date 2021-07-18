<?= "<?php\n" ?>

namespace <?= $namespace ?>;

use <?= $table->getFullName() ?>;
use <?= $entity->getFullName() ?>;
use <?= $form->getFullName() ?>;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Umbrella\CoreBundle\Controller\BaseController;
use function Symfony\Component\Translation\t;

/**
* @Route("<?= $route_path ?>")
*/
class <?= $class_name ?> extends BaseController
{
    /**
     * @Route
     */
    public function index(Request $request)
    {
        $table = $this->createTable(<?= $table->getShortName() ?>::class);
        $table->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getCallbackResponse();
        }

        return $this->render('<?= $index_template_name ?>', [
            'table' => $table
        ]);
    }

    /**
     * @Route(path="/edit/{id}", requirements={"id"="\d+"})
     */
    public function edit(Request $request, ?int $id = null)
    {
        if ($id === null) {
            $entity = new <?= $entity->getShortName() ?>();
<?php if ($tree_view) { ?>
            $entity->parent = $this->getRepository(<?= $entity->getShortName() ?>::class)->findRoot(true);
<?php } ?>
        } else {
            $entity = $this->findOrNotFound(<?= $entity->getShortName() ?>::class, $id);
        }

        $form = $this->createForm(<?= $form->getShortName() ?>::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistAndFlush($entity);

<?php if ($edit_on_modal) { ?>
            return $this->js()
                ->closeModal()
                ->reloadTable()
                ->toastSuccess(t('Item updated'));
<?php } else { ?>
            $this->toastSuccess(t('Item updated'));
            return $this->redirectToRoute('<?= $route_name ?>_edit', [
                'id' => $entity->id
            ]);
<?php } ?>
        }

<?php if ($edit_on_modal) { ?>
        return $this->js()
            ->modal('<?= $edit_template_name ?>', [
                'form' => $form->createView(),
                'entity' => $entity,
            ]);
<?php } else { ?>
        return $this->render('<?= $edit_template_name ?>', [
            'form' => $form->createView(),
            'entity' => $entity,
        ]);
<?php } ?>
    }

<?php if ($tree_view) { ?>
    /**
     * @Route("/move/{id}/{direction}", requirements={"id": "\d+"})
     */
    public function move(int $id, string $direction)
    {
        $repository = $this->getRepository(<?= $entity->getShortName() ?>::class);
        $entity = $this->findOrNotFound(<?= $entity->getShortName() ?>::class, $id);

        if ('up' === $direction) {
            $repository->moveUp($entity);
        } else {
            $repository->moveDown($entity);
        }

        return $this->js()
            ->reloadTable();
    }
<?php } ?>

    /**
     * @Route(path="/delete/{id}", requirements={"id"="\d+"})
     */
    public function delete(int $id)
    {
        $entity = $this->findOrNotFound(<?= $entity->getShortName() ?>::class, $id);
        $this->removeAndFlush($entity);

        return $this->js()
            ->reloadTable()
            ->toastSuccess(t('Item deleted'));
    }
}
