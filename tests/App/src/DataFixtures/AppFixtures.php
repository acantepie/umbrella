<?php

namespace Umbrella\AdminBundle\Tests\App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Umbrella\AdminBundle\Tests\App\Entity\AdminUser;

class AppFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadUser($manager);
    }

    private function loadUser(ObjectManager $manager): void
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
