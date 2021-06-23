<?php

namespace App\Controller\Admin;

use Symfony\Component\Routing\Annotation\Route;
use Umbrella\AdminBundle\Controller\AdminController;

/**
 * @Route("")
 */
class DefaultController extends AdminController
{
    /**
     * @Route("")
     */
    public function index()
    {
        return $this->render('admin/default/index.html.twig');
    }
}
