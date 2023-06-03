<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $movie = new Movie();
        $movie->setTitle('Dark Knight');
        $movie->setReleaseYear(2008);
        $movie->setDescription('This is the description of Dark Knight');
        $movie->setImagePath('https://cdn.pixabay.com/photo/2023/05/16/18/13/batman-7998317_640.jpg');
        
        // This lines add data to pivot table
        $movie->addActor($this->getReference('actor_1'));
        $movie->addActor($this->getReference('actor_2'));

        $manager->persist($movie);

        $movie2 = new Movie();
        $movie2->setTitle('Avengers: Endgame');
        $movie2->setReleaseYear(2019);
        $movie2->setDescription('This is the description of Avengers: Endgame');
        $movie2->setImagePath('https://cdn.pixabay.com/photo/2020/07/02/19/36/marvel-5364165_1280.jpg');
        
        // This lines add data to pivot table
        $movie2->addActor($this->getReference('actor_3'));
        $movie2->addActor($this->getReference('actor_4'));
        
        $manager->persist($movie2);

        $manager->flush();

    }
}
