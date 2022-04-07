<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WebPageController extends AbstractController
{
    /**
     * @Route("/", name="app_contact")
     */
    public function index(): Response
    {
        return $this->render('web_page/index.html.twig', [
            'controller_name' => 'WebPageController',
        ]);
    }
}
