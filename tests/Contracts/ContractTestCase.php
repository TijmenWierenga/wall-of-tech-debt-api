<?php

declare(strict_types=1);

namespace App\Tests\Contracts;

use League\OpenAPIValidation\PSR7\Exception\Validation\InvalidBody;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\PSR7\OperationAddress;
use League\OpenAPIValidation\PSR7\ResponseValidator;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use League\OpenAPIValidation\Schema\BreadCrumb;
use League\OpenAPIValidation\Schema\Exception\SchemaMismatch;
use League\OpenAPIValidation\Schema\Exception\TypeMismatch;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Response;

class ContractTestCase extends KernelTestCase
{
    protected ResponseValidator $validator;

    public function setUp(): void
    {
        $this->validator = (new ValidatorBuilder())
            ->fromYamlFile(__DIR__ . '/../../public/openapi.yaml')
            ->getResponseValidator();
    }

    protected function validateResponse(OperationAddress $operation, Response $response): void
    {
        $psrFactory = self::$container->get(PsrHttpFactory::class);
        $psrResponse = $psrFactory->createResponse($response);

        try {
            $this->validator->validate($operation, $psrResponse);
        } catch (ValidationFailed $e) {
            $message = $e->getMessage();

            if ($e->getPrevious() === null) {
                $this->fail($message);
            }

            if ($e->getPrevious() instanceof SchemaMismatch) {
                $breadCrumb = $e->getPrevious()->dataBreadCrumb();

                if ($breadCrumb instanceof BreadCrumb) {
                    $path = implode('.', $breadCrumb->buildChain());
                } else {
                    $path = '';
                }

                $message .= ':' . PHP_EOL . 'Path: ' . $path . PHP_EOL . 'Error: ' . $e->getPrevious()->getMessage();
            }

            $this->fail($message);
        }

        $this->addToAssertionCount(1);
    }
}
