<?php

namespace Umbrella\AdminBundle\Tests\App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Umbrella\CoreBundle\Controller\BaseController;

#[Route('/')]
class DefaultController extends BaseController
{

    #[Route('', name: 'test.home')]
    public function index(): Response
    {
        return $this->render('@UmbrellaAdmin/layout.html.twig');
    }

}
