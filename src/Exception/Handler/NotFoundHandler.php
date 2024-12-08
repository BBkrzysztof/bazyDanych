<?php

namespace App\Exception\Handler;

use App\Exception\JsonBadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NotFoundHandler
{
    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event): void
    {

        $exception = $event->getThrowable();

        if (!($exception instanceof NotFoundHttpException)) {
            return;
        }

        $errors = [
            'data' => $exception->getMessage() ?: 'Resource not found'
        ];

        $event->setResponse(new JsonResponse($errors, Response::HTTP_NOT_FOUND));
    }
}