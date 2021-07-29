<?php

namespace Umbrella\AdminBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Umbrella\AdminBundle\Menu\BaseAdminMenu;

class BootTest extends KernelTestCase
{

    public function testBoot()
    {
        $this->assertTrue($this->getContainer()->has(BaseAdminMenu::class));
    }

}