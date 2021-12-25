<?php

namespace Umbrella\AdminBundle\Tests\Controller;

use Umbrella\AdminBundle\Tests\TestApplication\AppTestCase;
use Umbrella\AdminBundle\Tests\TestApp\Entity\AdminUser;

class SecurityControllerTest extends AppTestCase
{
    public function testLogin()
    {
        $client = static::createClient();
        static::loadFixtures();

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


        // am i logged ?
        $tkStorage = $this->getContainer()->get('security.untracked_token_storage');

        $this->assertNotNull($tkStorage->getToken());
        $this->assertInstanceOf(AdminUser::class, $tkStorage->getToken()->getUser());

        /** @var AdminUser $u */
        $u = $tkStorage->getToken()->getUser();

        // I see my name on page ?
        $this->assertSelectorTextContains('div', $u->getFullName());
    }

    public function testInvalidCredentialsLogin()
    {
        $client = static::createClient();
        static::loadFixtures();

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

    public function testDisabledAccountLogin()
    {
        $client = static::createClient();
        static::loadFixtures();

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
