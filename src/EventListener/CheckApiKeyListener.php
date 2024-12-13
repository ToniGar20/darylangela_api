<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CheckApiKeyListener
{
    private string $apiKey;

    public function __construct(ParameterBagInterface $params)
    {
        $this->apiKey = $params->get('API_KEY');
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        if (!$request->getPathInfo() || !str_contains($request->getPathInfo(), '/api')) {
            return;
        }

        $apiKeyHeader = $request->headers->get('X-API-KEY');

        if ($apiKeyHeader !== $this->apiKey) {
            $event->setResponse(new JsonResponse([
                'error' => 'Invalid API Key'
            ], Response::HTTP_FORBIDDEN));
        }
    }
}
