<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ActorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $actor = new Actor();
        $actor->setName('Christian Bale');
        $manager->persist($actor);
        
        $actor2 = new Actor();
        $actor2->setName('Heath Ledger');
        $manager->persist($actor2);

        $actor3 = new Actor();
        $actor3->setName('Robert Downey Jr');
        $manager->persist($actor3);

        $actor4 = new Actor();
        $actor4->setName('Chris Evans');
        $manager->persist($actor4);

        $manager->flush();

        $this->setReference('actor_1', $actor);
        $this->setReference('actor_2', $actor2);
        $this->setReference('actor_3', $actor3);
        $this->setReference('actor_4', $actor4);
    }
}
