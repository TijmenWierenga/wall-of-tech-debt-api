<?php

declare(strict_types=1);

namespace App\Tests\Contracts;

use App\Domain\Security\User;
use App\Domain\Security\UserRepository;
use App\Tests\DatabasePrimer;
use Doctrine\ORM\EntityManagerInterface;
use League\OpenAPIValidation\PSR7\OperationAddress;
use Ramsey\Uuid\Uuid;

final class MeGetTest extends ContractTestCase
{
    use DatabasePrimer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->prepareDatabaseSchema(self::$container->get(EntityManagerInterface::class));
    }

    public function testItReturnsTheCurrentlyAuthenticatedUser(): void
    {
        $userRepository = self::$container->get(UserRepository::class);
        $user = User::new(Uuid::uuid4(), 'tijmen', 'fake-password');
        $user->setFirstName('Tijmen');
        $user->setLastName('Wierenga');
        $userRepository->save($user);

        $token = $this->getAccessTokenFor($user);

        $this->client->request(
            'GET',
            'me',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => "Bearer ${token}"
            ],
        );

        $response = $this->client->getResponse();

        $this->validateResponse(new OperationAddress('/me', 'get'), $response);
    }
}
