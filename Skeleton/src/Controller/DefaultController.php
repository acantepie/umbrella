<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("")
     */
    public function index()
    {
        return $this->render('index.html.twig');
    }
}
