<?php

namespace App\Logger\EventListener;

use App\Logger\LoggerAnnotation;
use App\Logger\LoggerService;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Controller\ErrorController;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class AnnotationListener
{
    private Reader $annotationReader;
    private LoggerService $loggerService;
    private EntityManagerInterface $entityManager;

    public function __construct(
        Reader                 $annotationReader,
        LoggerService          $loggerService,
        EntityManagerInterface $entityManager
    )
    {
        $this->annotationReader = $annotationReader;
        $this->loggerService = $loggerService;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws \ReflectionException
     */
    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();
        if ($controller instanceof ErrorController) {
            return;
        }

        [$controller, $method] = $controller;

        $annotations = $this->annotationReader->getMethodAnnotations(
            new \ReflectionMethod($controller, $method)
        );

        foreach ($annotations as $annotation) {
            if ($annotation instanceof LoggerAnnotation) {
                $this->loggerService->setAction($annotation->action);
                $this->entityManager->beginTransaction();
            }
        }
    }

}