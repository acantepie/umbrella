<?php

namespace Umbrella\AdminBundle\Tests\TestApp\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Umbrella\AdminBundle\Tests\TestApp\Entity\AdminUser;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    /**
     * AppFixtures constructor.
     */
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUser($manager);
    }

    private function loadUser(ObjectManager $manager)
    {
        $u = new AdminUser();
        $u->firstname = 'John';
        $u->lastname = 'Doe';
        $u->email = 'john.doe@ok.com';
        $u->plainPassword = '1234';
        $u->password = $this->hasher->hashPassword($u, $u->plainPassword);
        $manager->persist($u);

        $u = new AdminUser();
        $u->firstname = 'John';
        $u->lastname = 'Doe';
        $u->email = 'john.doe@ko.com';
        $u->plainPassword = '1234';
        $u->active = false;
        $u->password = $this->hasher->hashPassword($u, $u->plainPassword);
        $manager->persist($u);

        $manager->flush();
    }


}