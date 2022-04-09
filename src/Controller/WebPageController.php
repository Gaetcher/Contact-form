<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\MessageFeed;
use App\Form\MessageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class WebPageController extends AbstractController
{
    /**
     * @Route("/", name="app_contact")
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $message = new Message();
        $contactForm = $this->createForm(MessageType::class, $message);

        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $messageFeed = $entityManager->getRepository(MessageFeed::class)->findOneBy(['email' => $message->getEmail()]);

            if ($messageFeed == NULL) {
                $messageFeed = new MessageFeed();
                $messageFeed->setEmail($message->getEmail());
                $entityManager->persist($messageFeed);
            }
            
            $message->setMessageFeed($messageFeed);

            $entityManager->persist($message);
            $entityManager->flush();
        }

        return $this->render('web_page/contact.html.twig', [
            'contactForm' => $contactForm->createView()
        ]);
    }
}
