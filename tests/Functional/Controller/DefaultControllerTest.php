<?php

namespace Umbrella\AdminBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;

class DefaultControllerTest extends WebTestCase
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
