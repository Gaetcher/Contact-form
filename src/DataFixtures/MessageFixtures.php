<?php

namespace App\DataFixtures;

use App\Entity\Message;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\MessageFeedFixtures;
use App\Repository\MessageFeedRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MessageFixtures extends Fixture implements DependentFixtureInterface
{
    private $messageFeedRepository;

    public function __construct(MessageFeedRepository $messageFeedRepository)
    {
        $this->messageFeedRepository = $messageFeedRepository;
    }

    public function load(ObjectManager $manager)
    {
        for ($iterrator = 1; $iterrator <= 40; $iterrator++) { 
            $message = new Message();

            $key = $iterrator;

            if ($iterrator > 20) {
                $key = $iterrator - 20;
            }

            $messageFeed = $this->messageFeedRepository->findOneBy(['id' => $key]);
            $email = "email-test-" . $key . "@contact-form.fr";
            $contactMessage = "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Illo commodi suscipit inventore, molestias, architecto placeat nulla labore vitae hic perspiciatis, quia consequuntur aut a adipisci ut culpa? Necessitatibus rerum culpa, dolores cumque neque nesciunt tempore error? Ratione consequatur ex itaque reprehenderit odit quasi harum repellendus alias, ab deserunt asperiores ipsam dicta, facere non rem saepe eius nihil quidem, nesciunt nisi qui placeat a dolores. Illo, earum. Reprehenderit obcaecati possimus necessitatibus dignissimos nihil quae quis consequuntur voluptatibus! Quos, architecto cum! Aliquid nulla minus sit, debitis quo delectus dolorum labore reiciendis consequuntur. Rerum aperiam ipsum eius officiis officia facilis, eos ratione sunt temporibus minus incidunt ea praesentium beatae magni accusamus velit ad, harum nemo, cupiditate ducimus inventore voluptatibus maiores porro labore. Enim expedita porro id dignissimos ipsam. Ut odit quos similique accusamus veritatis et quis quas earum aperiam assumenda hic dicta natus nulla sint placeat quae cum, repellat ad facilis magni. Vitae ipsa quia iure, hic nulla vero expedita natus, sint eveniet debitis nostrum repellat culpa maxime magnam dolorum, doloribus voluptatem distinctio suscipit animi repellendus laboriosam deserunt. Modi vitae quisquam excepturi rem et officia amet deleniti consequatur aperiam aliquid vel, rerum voluptatibus sit corporis nam quaerat voluptate totam aliquam. Sit, velit odit.";

            $message->setEmail($email)
                ->setLastname('Lorem-' . $key)
                ->setFirstName('Ispsum-' . $key)
                ->setSubject('Lorem Ipsum-' . $iterrator)
                ->setMessage($contactMessage)
                ->setMessageFeed($messageFeed);

            $manager->persist($message);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            MessageFeedFixtures::class,
        );
    }
}