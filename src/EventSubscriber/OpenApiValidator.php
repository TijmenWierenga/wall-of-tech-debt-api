<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class OpenApiValidator implements EventSubscriberInterface
{
    private PsrHttpFactory $psrHttpFactory;

    public function __construct(PsrHttpFactory $psrHttpFactory)
    {
        $this->psrHttpFactory = $psrHttpFactory;
    }

    public static function getSubscribedEvents()
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

        $validator = (new ValidatorBuilder())
            ->fromYamlFile(__DIR__ . '/../../public/openapi.yaml')
            ->getServerRequestValidator();

        $validator->validate($psrRequest);
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
