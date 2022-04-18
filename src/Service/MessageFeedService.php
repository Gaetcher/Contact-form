<?php
namespace App\Service;

use App\Entity\MessageFeed;
use App\Service\AbstractService;

class MessageFeedService extends AbstractService
{
    public function getPagination($paginationActive = 1)
    {
        $messageFeedRepository = $this->entityManager->getRepository(MessageFeed::class);
        $maxPagination = $messageFeedRepository->findMaxPagination();
        $messageFeeds = null;

        if (is_int($paginationActive) && $paginationActive > 0 && $paginationActive <= $maxPagination) {
            $messageFeeds = $messageFeedRepository->selectByPagination($paginationActive);
        }

        return [
            'paginationActive' => $paginationActive,
            'maxPagination' => $maxPagination,
            'messageFeeds' => $messageFeeds,
        ];
    }
}