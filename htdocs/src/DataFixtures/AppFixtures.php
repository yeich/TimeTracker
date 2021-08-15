<?php

namespace App\DataFixtures;

use App\Entity\ProjectPriority;
use App\Entity\ProjectState;
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
        $user->setLastname('Eich');

        $manager->persist($user);


        $projectPriority = new ProjectPriority();
        $projectPriority->setName('Low');
        $projectPriority->setColorCode('#00f218');
        $projectPriority->setPriority(1);
        $manager->persist($projectPriority);

        $projectPriority = new ProjectPriority();
        $projectPriority->setName('Medium');
        $projectPriority->setColorCode('#F9BF00');
        $projectPriority->setPriority(2);
        $manager->persist($projectPriority);

        $projectPriority = new ProjectPriority();
        $projectPriority->setName('High');
        $projectPriority->setColorCode('#F20000');
        $projectPriority->setPriority(3);
        $manager->persist($projectPriority);


        $projectState = new ProjectState();
        $projectState->setName('Design');
        $projectState->setFilledQuarters(1);
        $manager->persist($projectState);

        $projectState = new ProjectState();
        $projectState->setName('Development');
        $projectState->setFilledQuarters(2);
        $manager->persist($projectState);

        $projectState = new ProjectState();
        $projectState->setName('Final / Testing');
        $projectState->setFilledQuarters(3);
        $manager->persist($projectState);

        $manager->flush();
    }
}
