<?php

namespace Security\EventListener;

use Doctrine\Common\Annotations\Reader;
use Security\Annotation\RoleGuard;
use Security\Service\Security;
use Symfony\Component\HttpKernel\Controller\ErrorController;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class RoleGuardListener
{
    private Reader $annotationReader;
    private Security $security;

    public function __construct(Reader $annotationReader, Security $security)
    {
        $this->annotationReader = $annotationReader;
        $this->security = $security;
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

        $annotations = $this->annotationReader->getMethodAnnotations(new \ReflectionMethod($controller, $method));

        foreach ($annotations as $annotation) {
            if ($annotation instanceof RoleGuard) {
                $role = $this->security->getUser()->getRole();

                if (!in_array($role, $annotation->roles)){
                    throw new AccessDeniedHttpException('You do not have permission to perform this action.');
                }

            }
        }

    }
}