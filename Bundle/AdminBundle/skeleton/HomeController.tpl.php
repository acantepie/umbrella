<?php echo "<?php\n"; ?>

namespace <?php echo $namespace; ?>;

use Symfony\Component\Routing\Annotation\Route;
use Umbrella\CoreBundle\Controller\BaseController;

/**
 * @Route("<?php echo $route['base_path']; ?>")
 */
class <?php echo $class_name; ?> extends BaseController
{

    /**
     * @Route("")
     */
    public function index()
    {
        return $this->render('<?php echo $template; ?>');
    }

}