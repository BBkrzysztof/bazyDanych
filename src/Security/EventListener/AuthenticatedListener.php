<?php

namespace Security\EventListener;

use Doctrine\Common\Annotations\Reader;
use Security\Annotation\Authenticated;
use Security\Service\Security;
use Symfony\Component\HttpKernel\Controller\ErrorController;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthenticatedListener
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
            if ($annotation instanceof Authenticated) {
                if(!$this->security->getUser() || !$this->security->getToken()){
                    throw new UnauthorizedHttpException('','Authentication required');
                }
            }
        }
    }
}