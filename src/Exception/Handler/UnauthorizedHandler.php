<?php

namespace App\Exception\Handler;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class UnauthorizedHandler
{
    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event): void
    {

        $exception = $event->getThrowable();

        if (!($exception instanceof UnauthorizedHttpException)) {
            return;
        }

        $errors = [
            'data' => $exception->getMessage()
        ];

        $event->setResponse(new JsonResponse($errors, Response::HTTP_UNAUTHORIZED));
    }
}