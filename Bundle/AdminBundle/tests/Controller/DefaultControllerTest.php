<?php

namespace Umbrella\AdminBundle\Tests\Controller;

use Umbrella\AdminBundle\Tests\Functional\AppTestCase;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;

class DefaultControllerTest extends AppTestCase
{
    public function test()
    {
        $client = static::createClient();
        static::loadFixtures();

        $client->request('GET', '/');

        $appName = $this->getContainer()->get(UmbrellaAdminConfiguration::class)->appName();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('title', $appName);
    }
}
