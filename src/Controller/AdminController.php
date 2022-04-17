<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MessageFeedRepository;
use App\Service\MessageFeedService;
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
    const CODE_PAGINATION_OK = 2001;
    const CODE_MESSAGE_STATUS_TOGGLER_OK = 2002;

    /**
     * @Route("/", name="index")
     */
    public function index(MessageFeedService $messageFeedService): Response
    {
        $messageFeedPagination = $messageFeedService->getPagination();

        return $this->render('admin/index.html.twig', $messageFeedPagination);
    }


    /**
     * @Route("/ajax-pagination", name="ajax_pagination")
     */
    public function ajaxPagination(Request $request, MessageFeedService $messageFeedService, EntityManagerInterface $entityManager): Response
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

        $messageFeedPagination = $messageFeedService->getPagination($decodedRequest['pageRequested']);

        if ($messageFeedPagination['messageFeeds'] !== null) {

            $template = $this->renderView('admin/includes/_message_feed.html.twig', [
                'messageFeeds' => $messageFeedPagination['messageFeeds'],
            ]);

            return new JsonResponse([
                'content' => [
                    'code' => AdminController::CODE_PAGINATION_OK,
                    'template' => $template,
                    'paginationActive' => $decodedRequest['pageRequested']
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

    /**
     * @Route("/ajax-message-status-toggler", name="ajax_message_status_toggler")
     */
    public function messageStatusToggler(Request $request, EntityManagerInterface $entityManager): Response
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

        $targetMessage = $entityManager->getRepository(Message::class)->findOneBy(['id' => $decodedRequest['target']]);

        if ($targetMessage) {
            $targetMessage->setIsProcessed(!$targetMessage->getIsProcessed());
            $entityManager->persist($targetMessage);
            $entityManager->flush();
            return new JsonResponse([
                'content' => [
                    'code' => AdminController::CODE_MESSAGE_STATUS_TOGGLER_OK,
                    'target' => $targetMessage->getId(),
                    'status' => $targetMessage->getIsProcessed(),
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
