<?php

declare(strict_types=1);

namespace App\Services;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ResponseBuilder implements ResponseBuilderInterface
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function getResponse(int $code, string $message): Response
    {
        $result['code'] = $code;
        $result['body'] = [
            'code' => $result['code'],
            'message' => $message,
        ];

        $body = $this->serializer->serialize($result['body'], 'json');

        $response = new Response($body, $result['code']);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
