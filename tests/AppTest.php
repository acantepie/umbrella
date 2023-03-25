<?php

namespace Umbrella\AdminBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppTest extends WebTestCase
{
    public function test(): void
    {
        self::bootKernel();
    }

}