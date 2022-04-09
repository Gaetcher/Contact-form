<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WebPageController extends AbstractController
{
    /**
     * @Route("/", name="app_contact")
     */
    public function index(): Response
    {
        $mesage = new Message();
        $contactForm = $this->createForm(MessageType::class, $mesage);
        return $this->render('web_page/contact.html.twig', [
            'contactForm' => $contactForm->createView()
        ]);
    }
}
