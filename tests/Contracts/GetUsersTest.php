<?php

declare(strict_types=1);

namespace App\Tests\Contracts;

use App\Domain\Security\User;
use App\Domain\Security\UserRepository;
use App\Tests\DatabasePrimer;
use Doctrine\ORM\EntityManagerInterface;
use League\OpenAPIValidation\PSR7\OperationAddress;
use Ramsey\Uuid\Uuid;

final class GetUsersTest extends ContractTestCase
{
    use DatabasePrimer;

    public function testAllUsersResponse(): void
    {
        $this->prepareDatabaseSchema(static::$container->get(EntityManagerInterface::class));

        $userRepository = static::$container->get(UserRepository::class);

        $user = User::new(Uuid::uuid4(), 'tijmen', 'fake-password');

        $userRepository->save($user);

        $token = $this->getAccessTokenFor($user);

        $this->client->request(
            'GET',
            'users',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => "Bearer ${token}"
            ],
        );

        $response = $this->client->getResponse();

        $this->validateResponse(new OperationAddress('/users', 'get'), $response);
    }
}
