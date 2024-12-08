<?php

namespace Security\EventListener;

use App\Exception\JsonBadRequestException;
use Security\Annotation\RequiredFields;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\HttpKernel\Controller\ErrorController;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RequiredFieldsListener
{
    private Reader $annotationReader;

    public function __construct(Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
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
            if ($annotation instanceof RequiredFields) {
                $request = $event->getRequest();

                $missingFields = [];

                foreach ($annotation->fields as $field) {
                    if (!$request->request->has($field)) {
                        $missingFields[$field] = 'is Required';
                    }
                }

                if (!empty($missingFields)) {
                    throw new JsonBadRequestException($missingFields);
                }
            }
        }
    }
}