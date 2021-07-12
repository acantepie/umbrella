<?php

namespace App\Controller\Admin;

use Symfony\Component\Routing\Annotation\Route;
use Umbrella\CoreBundle\Controller\BaseController;

/**
 * @Route("")
 */
class DefaultController extends BaseController
{
    /**
     * @Route("")
     */
    public function index()
    {
        return $this->render('admin/default/index.html.twig');
    }
}
