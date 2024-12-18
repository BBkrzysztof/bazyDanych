<?php

namespace App\Exception\Handler;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AccessDeniedHandler
{
    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event): void
    {

        $exception = $event->getThrowable();

        if (!($exception instanceof AccessDeniedHttpException)) {
            return;
        }

        $errors = [
            'data' => $exception->getMessage() ?: "You dont have permissions"
        ];

        $event->setResponse(new JsonResponse($errors, Response::HTTP_FORBIDDEN));
    }
}