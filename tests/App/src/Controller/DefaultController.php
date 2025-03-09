<?php

namespace Umbrella\AdminBundle\Tests\App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Umbrella\AdminBundle\Lib\Controller\AdminController;

#[Route('/')]
class DefaultController extends AdminController
{
    #[Route('', name: 'test.home')]
    public function index(): Response
    {
        return $this->render('@UmbrellaAdmin/layout.html.twig');
    }
}
