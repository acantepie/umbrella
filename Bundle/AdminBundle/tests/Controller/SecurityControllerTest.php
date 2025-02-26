<?php

namespace Umbrella\AdminBundle\Tests\Controller;

use Umbrella\AdminBundle\Tests\AppTestCase;

class SecurityControllerTest extends AppTestCase
{
    public function testLogin(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        // login page
        $this->assertSelectorTextContains('button[type=submit]', 'Se connecter');

        // login success
        $form = $crawler->selectButton('Se connecter')
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

        $crawler = $client->request('GET', '/login');


        // login success
        $form = $crawler->selectButton('Se connecter')
            ->form([
                '_username' => 'john.doe@ok.com',
                '_password' => '12345'
            ]);
        $client->submit($form);
        $client->followRedirect();

        $this->assertSelectorTextContains('.alert-warning', 'Identifiants invalides');
    }

    public function testDisabledAccountLogin(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');


        // login success
        $form = $crawler->selectButton('Se connecter')
            ->form([
                '_username' => 'john.doe@ko.com',
                '_password' => '1234'
            ]);
        $client->submit($form);
        $client->followRedirect();

        $this->assertSelectorTextContains('.alert-warning', 'Votre compte est désactivé');
    }
}
