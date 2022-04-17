<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class AbstractService
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var User
     */
    protected $user;
    
    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->user = $security->getUser();
    }
}