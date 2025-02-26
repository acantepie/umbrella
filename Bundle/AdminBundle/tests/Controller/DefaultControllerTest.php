<?php

namespace Umbrella\AdminBundle\Tests\Controller;

use Umbrella\AdminBundle\Tests\AppTestCase;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;

class DefaultControllerTest extends AppTestCase
{
    public function test(): void
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $appName = $this->getContainer()->get(UmbrellaAdminConfiguration::class)->appName();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('title', $appName);
    }
}
