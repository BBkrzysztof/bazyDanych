<?php

namespace App\Exception\Handler;

use App\Exception\JsonBadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BadRequestHandler
{
    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event): void
    {

        $exception = $event->getThrowable();

        if (!($exception instanceof BadRequestHttpException)) {
            return;
        }

        $errors = [
            'data' => [
                $exception instanceof JsonBadRequestException ? $exception->getPayload() : $exception->getMessage()
            ]
        ];

        $event->setResponse(new JsonResponse($errors, Response::HTTP_BAD_REQUEST));
    }
}