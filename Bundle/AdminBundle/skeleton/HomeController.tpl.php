<?= "<?php\n" ?>

namespace <?= $namespace ?>;

use Symfony\Component\Routing\Annotation\Route;
use Umbrella\CoreBundle\Controller\BaseController;

/**
 * @Route("<?= $route['base_path'] ?>")
 */
class <?= $class_name ?> extends BaseController
{

    /**
     * @Route("")
     */
    public function index()
    {
        return $this->render('<?= $template ?>');
    }

}