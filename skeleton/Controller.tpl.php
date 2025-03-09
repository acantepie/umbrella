<?= "<?php\n" ?>

namespace <?= $namespace ?>;

use <?= $table->getFullName() ?>;
use <?= $entity->getFullName() ?>;
use <?= $form->getFullName() ?>;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Umbrella\AdminBundle\Lib\Controller\AdminController;
<?php if ($tree_table) { ?>
use <?= $repository->getFullName() ?>;
<?php } ?>

#[Route('<?= $route['base_path'] ?>')]
class <?= $class_name ?> extends AdminController
{

    #[Route('')]
    public function index(Request $request): Response
    {
        $table = $this->createTable(<?= $table->getShortName() ?>::class);
        $table->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getCallbackResponse();
        }

        return $this->render('<?= $index_template ?>', [
            'table' => $table
        ]);
    }

    #[Route('/edit/{id}', requirements: ['id' => '\d+'])]
    public function edit(<?php if ($tree_table) { ?><?= $repository->getShortName() ?> $repository, <?php } ?>Request $request, ?int $id = null): Response
    {
        if ($id === null) {
            $entity = new <?= $entity->getShortName() ?>();
<?php if ($tree_table) { ?>
            $entity->parent = $repository->findRoot(true);
<?php } ?>
        } else {
            $entity = $this->findOrNotFound(<?= $entity->getShortName() ?>::class, $id);
        }

        $form = $this->createForm(<?= $form->getShortName() ?>::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistAndFlush($entity);

<?php if ('modal' === $edit_view_type) { ?>
            return $this->js()
                ->closeModal()
                ->reloadTable()
                ->toastSuccess('Item updated');
<?php } else { ?>
            $this->toastSuccess('Item updated');
            return $this->redirectToRoute('<?= $route['name_prefix'] ?>_edit', [
                'id' => $entity->id
            ]);
<?php } ?>
        }

<?php if ('modal' === $edit_view_type) { ?>
        return $this->js()
            ->modal('<?= $edit_template ?>', [
                'form' => $form->createView(),
                'entity' => $entity,
            ]);
<?php } else { ?>
        return $this->render('<?= $edit_template ?>', [
            'form' => $form->createView(),
            'entity' => $entity,
        ]);
<?php } ?>
    }

<?php if ($tree_table) { ?>
    #[Route('/move/{id}/{direction}', requirements: ['id' => '\d+'])]
    public function move(<?php if ($tree_table) { ?><?= $repository->getShortName() ?> $repository, <?php } ?>int $id, string $direction): Response
    {
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

    #[Route('/delete/{id}', requirements: ['id' => '\d+'])]
    public function delete(int $id): Response
    {
        $entity = $this->findOrNotFound(<?= $entity->getShortName() ?>::class, $id);
        $this->removeAndFlush($entity);

        return $this->js()
            ->reloadTable()
            ->toastSuccess('Item deleted');
    }
}
