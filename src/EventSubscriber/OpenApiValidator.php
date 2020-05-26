<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\PSR7\ServerRequestValidator;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class OpenApiValidator implements EventSubscriberInterface
{
    private PsrHttpFactory $psrHttpFactory;
    private ServerRequestValidator $requestValidator;

    public function __construct(PsrHttpFactory $psrHttpFactory, ServerRequestValidator $requestValidator)
    {
        $this->psrHttpFactory = $psrHttpFactory;
        $this->requestValidator = $requestValidator;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'validateRequest',
            KernelEvents::EXCEPTION => 'handleValidationErrors'
        ];
    }

    public function validateRequest(ControllerEvent $event): void
    {
        $request = $event->getRequest();
        $psrRequest = $this->psrHttpFactory->createRequest($request);

        $this->requestValidator->validate($psrRequest);
    }

    public function handleValidationErrors(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();

        if (! $e instanceof ValidationFailed) {
            return;
        }

        $response = new JsonResponse([
            'message' => $e->getMessage(),
        ], JsonResponse::HTTP_BAD_REQUEST);

        $event->setResponse($response);
    }
}
