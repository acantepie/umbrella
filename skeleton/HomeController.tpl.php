<?= "<?php\n" ?>

namespace <?= $namespace ?>;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Umbrella\AdminBundle\Lib\Controller\AdminController;

#[Route('<?= $route['base_path'] ?>')]
class <?= $class_name ?> extends AdminController
{

    #[Route('')]
    public function index(): Response
    {
        return $this->render('<?= $template ?>');
    }

}
