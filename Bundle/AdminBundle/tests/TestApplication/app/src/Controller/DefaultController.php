<?php

namespace Umbrella\AdminBundle\Tests\TestApp\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Umbrella\CoreBundle\Controller\BaseController;

#[Route('/')]
class DefaultController extends BaseController
{

    #[Route('', name: 'test.home')]
    public function index()
    {
        return $this->render('@UmbrellaAdmin/layout.html.twig');
    }

}
