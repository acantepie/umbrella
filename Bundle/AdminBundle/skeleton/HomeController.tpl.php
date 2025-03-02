<?= "<?php\n" ?>

namespace <?= $namespace ?>;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Umbrella\CoreBundle\Controller\BaseController;

#[Route('<?= $route['base_path'] ?>')]
class <?= $class_name ?> extends BaseController
{

    #[Route('')]
    public function index(): Response
    {
        return $this->render('<?= $template ?>');
    }

}
