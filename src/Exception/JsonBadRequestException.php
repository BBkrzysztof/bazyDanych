<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class JsonBadRequestException extends BadRequestHttpException
{
    private array $payload;

    public function __construct(?array $payload=[], ?string $message = '', ?\Throwable $previous = null, int $code = 0, array $headers = [])
    {
        parent::__construct($message, $previous, $code, $headers);
        $this->payload = $payload;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

}