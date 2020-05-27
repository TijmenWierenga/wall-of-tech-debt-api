<?php

declare(strict_types=1);

namespace App\Tests\Domain\Security;

use App\Domain\Security\TokenAuthenticator;
use App\Domain\Security\TokenService;
use App\Domain\Security\User;
use App\Domain\Security\UserRepositoryInMemory;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class TokenAuthenticatorTest extends TestCase
{
    private TokenAuthenticator $authenticator;
    private UserRepositoryInMemory $userRepository;
    private TokenService $tokenService;
    
    protected function setUp(): void
    {
        $this->userRepository = new UserRepositoryInMemory();
        $this->tokenService = new TokenService(random_bytes(32));
        $this->authenticator = new TokenAuthenticator($this->userRepository, $this->tokenService);
    }

    public function testItSupportsARequestWithAuthorizationHeader(): void
    {
        $request = Request::create('/issues');
        $request->headers->add([
            'Authorization' => 'Bearer some-token'
        ]);

        static::assertTrue($this->authenticator->supports($request));
    }

    public function testItReturnsA403ToIndicateAuthenticationIsNeeded(): void
    {
        $request = Request::create('/issues');
        $response = $this->authenticator->start($request);

        static::assertEquals(403, $response->getStatusCode());
    }

    public function testItExtractsTheTokenFromARequest(): void
    {
        $request = Request::create('/issues');
        $request->headers->add([
            'Authorization' => 'Bearer some-token'
        ]);

        static::assertEquals('Bearer some-token', $this->authenticator->getCredentials($request));
    }

    public function testItOnlyAcceptsABearerToken(): void
    {
        $this->expectException(AuthenticationException::class);

        $this->authenticator->getUser('NonBearer token', $this->createMock(UserProviderInterface::class));
    }

    public function testItFailsAuthenticationWhenTokenIsInvalid(): void
    {
        $this->expectException(AuthenticationException::class);

        $this->authenticator->getUser('Bearer fake-token', $this->createMock(UserProviderInterface::class));
    }

    public function testItAbortsAuthenticationWhenUserDoesNotExist(): void
    {
        $this->expectException(AuthenticationException::class);

        $user = User::new(Uuid::uuid4(), 'a-user', 'a-password'); // User is not saved
        $token = $this->tokenService->createToken($user);

        $this->authenticator->getUser(sprintf('Bearer %s', $token), $this->createMock(UserProviderInterface::class));
    }

    public function testItReturnsTheUserFromAValidToken(): void
    {
        $user = User::new(Uuid::uuid4(), 'a-user', 'a-password');
        $this->userRepository->users = new ArrayCollection([$user]); // Save the user
        $token = $this->tokenService->createToken($user);

        $result = $this->authenticator->getUser(
            sprintf('Bearer %s', $token),
            $this->createMock(UserProviderInterface::class)
        );

        static::assertEquals($user, $result);
    }
}
