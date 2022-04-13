<?php

namespace App\DataFixtures;

use App\Entity\MessageFeed;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class MessageFeedFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        
        for ($iterrator = 1; $iterrator <= 20; $iterrator++) { 
            $messageFeed = new MessageFeed();
            $email = "email-test-" . $iterrator . "@contact-form.fr";
            $messageFeed->setEmail($email);
            $manager->persist($messageFeed);
        }

        $manager->flush();
    }

}