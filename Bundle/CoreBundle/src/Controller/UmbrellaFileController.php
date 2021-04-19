<?php

namespace Umbrella\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Umbrella\CoreBundle\Component\UmbrellaFile\DownloadHandler;

/**
 * Class UmbrellaFileController
 */
class UmbrellaFileController extends AbstractController
{
    public function downloadAction(DownloadHandler $downloadHandler, Request $request)
    {
        $file = $downloadHandler->loadFromRequest($request);

        if (null === $file) {
            return new Response('Resource not found.', Response::HTTP_NOT_FOUND);
        }

        return $downloadHandler->downloadOrNotFound($file, false);
    }
}
