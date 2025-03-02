<?php

namespace Umbrella\CoreBundle\JsResponse;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class JsResponseFactory
{
    public function __construct(
        protected readonly TranslatorInterface $translator,
        protected readonly RouterInterface $router,
        protected readonly Environment $twig
    ) {
    }

    public function create(): JsResponse
    {
        return new JsResponse(
            $this->translator,
            $this->router,
            $this->twig
        );
    }
}
