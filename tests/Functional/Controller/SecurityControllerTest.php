<?php

namespace Umbrella\AdminBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Umbrella\AdminBundle\Tests\Functional\DbUtils;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin(): void
    {
        $client = static::createClient();
        DbUtils::initDb($client->getKernel());

        $crawler = $client->request('GET', '/login');

        // login page
        $this->assertSelectorTextContains('button[type=submit]', 'Sign in');

        // login success
        $form = $crawler->selectButton('Sign in')
            ->form([
                '_username' => 'john.doe@ok.com',
                '_password' => '1234'
            ]);
        $client->submit($form);
        $client->followRedirect();

        // I see my name on page ?
        $this->assertSelectorTextContains('div', 'john.doe@ok.com');
    }

    public function testInvalidCredentialsLogin(): void
    {
        $client = static::createClient();
        DbUtils::initDb($client->getKernel());

        $crawler = $client->request('GET', '/login');

        // login success
        $form = $crawler->selectButton('Sign in')
            ->form([
                '_username' => 'john.doe@ok.com',
                '_password' => '12345'
            ]);
        $client->submit($form);
        $client->followRedirect();

        $this->assertSelectorTextContains('.alert-warning', 'Invalid credentials.');
    }

    public function testDisabledAccountLogin(): void
    {
        $client = static::createClient();
        DbUtils::initDb($client->getKernel());

        $crawler = $client->request('GET', '/login');

        // login success
        $form = $crawler->selectButton('Sign in')
            ->form([
                '_username' => 'john.doe@ko.com',
                '_password' => '1234'
            ]);
        $client->submit($form);
        $client->followRedirect();

        $this->assertSelectorTextContains('.alert-warning', 'Account is disabled.');
    }
}
