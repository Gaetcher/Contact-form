<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MessageFeedRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(MessageFeedRepository $messageFeedRepository): Response
    {
        $paginationActive = 1;
        $maxPagination = $messageFeedRepository->findMaxPagination();
        $messageFeeds = $messageFeedRepository->selectByPagination();

        return $this->render('admin/index.html.twig', [
            'paginationActive' => $paginationActive,
            'maxPagination' => $maxPagination,
            'messageFeeds' => $messageFeeds,
        ]);
    }


    /**
     * @Route("/ajax-pagination", name="ajax_pagination")
     */
    public function ajaxPagination(Request $request, EntityManagerInterface $entityManager, MessageFeedRepository $messageFeedRepository): Response
    {
        $decodedRequest = json_decode($request->getContent(), true);
        $userFromRequestExists = $entityManager->getRepository(User::class)->findOneBy(['token' => $decodedRequest['uToken']]);
        
        if (!$userFromRequestExists) {
            return new JsonResponse([
                'error' => [
                    'code' => '404',
                    'message' => 'Resource not found'
                ]
            ], 404);
        }

        $pageRequested = $decodedRequest['pageRequested'];
        $maxPagination = $messageFeedRepository->findMaxPagination();
        
        if (is_int($pageRequested) && $pageRequested > 0 && $pageRequested <= $maxPagination) {
            $messageFeeds = $messageFeedRepository->selectByPagination($pageRequested);

            $template = $this->renderView('admin/includes/_message_feed.html.twig', [
                'messageFeeds' => $messageFeeds
            ]);
            
            return new JsonResponse([
                'content' => [
                    'template' => $template,
                    'paginationActive' => $pageRequested
                ]
            ], 200);
        }

        return new JsonResponse([
            'error' => [
                'code' => '404',
                'message' => 'Resource not found'
            ]
        ], 404);
    }
}
