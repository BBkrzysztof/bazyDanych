<?php

namespace App\Logger\EventListener;

use App\Entity\Log;
use App\Interface\LoggerInterface;
use App\Logger\LoggerService;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Security\Service\Security;

class DoctrineEventListener implements EventSubscriber
{
    private LoggerService $loggerService;
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(
        LoggerService          $loggerService,
        EntityManagerInterface $entityManager,
        Security               $security,
    )
    {
        $this->loggerService = $loggerService;
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function getSubscribedEvents(): array
    {
        return [Events::postPersist, Events::postUpdate, Events::postRemove];
    }

    public function postUpdate(LifecycleEventArgs $event): void
    {
        $this->log($event->getObject());
    }

    public function postPersist(LifecycleEventArgs $event): void
    {
        $this->log($event->getObject());
    }

    public function postRemove(LifecycleEventArgs $event): void
    {
        $this->log($event->getObject());
    }

    private function log(mixed $entity): void
    {
        if (!($entity instanceof LoggerInterface)) {
            return;
        }

        if(!$this->entityManager->getConnection()->isTransactionActive()){
            return;
        }

        $messags = $entity->getLoggerMessages();

        $action = $this->loggerService->getAction();

        if (!$action) {
            return;
        }

        $message = $messags[$action];

        $ticket = method_exists($entity, 'getTicket') ? $entity->getTicket() : null;

        $log = new Log($message, $this->security->getUser(), $ticket);

        $this->entityManager->persist($log);
        $this->entityManager->flush();


        $this->entityManager->commit();
    }
}