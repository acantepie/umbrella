<?php echo "<?php\n"; ?>

namespace <?php echo $namespace; ?>;

use <?php echo $table->getFullName(); ?>;
use <?php echo $entity->getFullName(); ?>;
use <?php echo $form->getFullName(); ?>;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Umbrella\CoreBundle\Controller\BaseController;
use function Symfony\Component\Translation\t;
<?php if ($tree_table) { ?>
use <?php echo $repository->getFullName(); ?>;
<?php } ?>

/**
* @Route("<?php echo $route['base_path']; ?>")
*/
class <?php echo $class_name; ?> extends BaseController
{
    /**
     * @Route
     */
    public function index(Request $request)
    {
        $table = $this->createTable(<?php echo $table->getShortName(); ?>::class);
        $table->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getCallbackResponse();
        }

        return $this->render('<?php echo $index_template; ?>', [
            'table' => $table
        ]);
    }

    /**
     * @Route(path="/edit/{id}", requirements={"id"="\d+"})
     */
    public function edit(<?php if ($tree_table) { ?><?php echo $repository->getShortName(); ?> $repository, <?php } ?>Request $request, ?int $id = null)
    {
        if ($id === null) {
            $entity = new <?php echo $entity->getShortName(); ?>();
<?php if ($tree_table) { ?>
            $entity->parent = $repository->findRoot(true);
<?php } ?>
        } else {
            $entity = $this->findOrNotFound(<?php echo $entity->getShortName(); ?>::class, $id);
        }

        $form = $this->createForm(<?php echo $form->getShortName(); ?>::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistAndFlush($entity);

<?php if ('modal' === $edit_view_type) { ?>
            return $this->js()
                ->closeModal()
                ->reloadTable()
                ->toastSuccess(t('Item updated'));
<?php } else { ?>
            $this->toastSuccess(t('Item updated'));
            return $this->redirectToRoute('<?php echo $route['name_prefix']; ?>_edit', [
                'id' => $entity->id
            ]);
<?php } ?>
        }

<?php if ('modal' === $edit_view_type) { ?>
        return $this->js()
            ->modal('<?php echo $edit_template; ?>', [
                'form' => $form->createView(),
                'entity' => $entity,
            ]);
<?php } else { ?>
        return $this->render('<?php echo $edit_template; ?>', [
            'form' => $form->createView(),
            'entity' => $entity,
        ]);
<?php } ?>
    }

<?php if ($tree_table) { ?>
    /**
     * @Route("/move/{id}/{direction}", requirements={"id": "\d+"})
     */
    public function move(<?php if ($tree_table) { ?><?php echo $repository->getShortName(); ?> $repository, <?php } ?>int $id, string $direction)
    {
        $entity = $this->findOrNotFound(<?php echo $entity->getShortName(); ?>::class, $id);

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
        $entity = $this->findOrNotFound(<?php echo $entity->getShortName(); ?>::class, $id);
        $this->removeAndFlush($entity);

        return $this->js()
            ->reloadTable()
            ->toastSuccess(t('Item deleted'));
    }
}
