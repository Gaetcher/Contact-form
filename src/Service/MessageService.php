<?php

namespace App\Service;

use App\Entity\Message;
use App\Entity\MessageFeed;
use App\Service\AbstractService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;

class MessageService extends AbstractService
{
    private $filesystem;


    public function __construct(Security $security, EntityManagerInterface $entityManager, Filesystem $filesystem)
    {
        parent::__construct($security, $entityManager);
        $this->filesystem = $filesystem;
    }

    public function createMessage($message)
    {
        $messageFeed = $this->entityManager->getRepository(MessageFeed::class)->findOneBy(['email' => $message->getEmail()]);
        if ($messageFeed == NULL) {
            $messageFeed = new MessageFeed();
            $messageFeed->setEmail($message->getEmail());
            $this->entityManager->persist($messageFeed);
        }
        
        $message->setMessageFeed($messageFeed);
        
        $this->entityManager->persist($message);
        $this->entityManager->flush();
    }

    public function addMessageToFile(Message $message)
    {
        $directory = $this->setDirectory();
        $filename = $message->getCreatedAt()->format('Y-m-d--h-i-s') . '_' . $message->getEmail() . '.json';
        $newFilePath = $directory . '/' . $filename;
        
        $messageJson = $this->jsonEncodeMessage($message);

        try {
            if (!$this->filesystem->exists($newFilePath)) {
                $this->filesystem->touch($newFilePath);
                $this->filesystem->chmod($newFilePath, 0777);
                $this->filesystem->dumpFile($newFilePath, $messageJson);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function setDirectory()
    {
        $targetPath = '../../Contacts';

        try {
            if (!$this->filesystem->exists($targetPath)) {
                $this->filesystem->mkdir($targetPath, 0777);
            }
            return $targetPath;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function jsonEncodeMessage($message)
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = new ObjectNormalizer(null, null, null, new ReflectionExtractor());

        $serializer = new Serializer([new DateTimeNormalizer() , $normalizers], $encoders);

        return $serializer->serialize($message, 'json', ['ignored_attributes' => ['messageFeed', 'isProcessed']]);
    }
}
