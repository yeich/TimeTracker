<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {

        $user = new User();
        $user->setEmail('yannick.eich@pm.me');
        $user->setPassword('$2y$13$Re5gOa9zWnf.GQ87vdUbgOfZQNktIrvC92CjXoaICSDPimQfndoPK');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setFirstname('Yannick');

        $manager->persist($user);
        $manager->flush();
    }
}
