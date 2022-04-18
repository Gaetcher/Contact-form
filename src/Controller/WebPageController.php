<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\MessageFeed;
use App\Form\MessageType;
use App\Service\MessageService;
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
    public function index(Request $request, MessageService $messageService): Response
    {
        $message = new Message();
        $contactForm = $this->createForm(MessageType::class, $message);

        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $messageService->createMessage($message);
            $messageService->addMessageToFile($message);
        }

        return $this->render('web_page/contact.html.twig', [
            'contactForm' => $contactForm->createView()
        ]);
    }
}
