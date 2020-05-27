<?php

namespace App\Tests\Security;

use App\Domain\Security\LoginService;
use App\Domain\Security\TokenService;
use App\Domain\Security\User;
use App\Domain\Security\UserNotFoundException;
use App\Domain\Security\UserRepositoryInMemory;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class LoginServiceTest extends TestCase
{
    private UserRepositoryInMemory $userRepository;
    private UserPasswordEncoderInterface $userPasswordEncoder;
    private TokenService $tokenService;
    private LoginService $loginService;

    protected function setUp(): void
    {
        $this->userRepository = new UserRepositoryInMemory();
        $this->userPasswordEncoder = new UserPasswordEncoderFake();
        $this->tokenService = new TokenService(random_bytes(32));
        $this->loginService = new LoginService($this->userRepository, $this->userPasswordEncoder, $this->tokenService);
    }

    public function testItAbortsAuthenticationWhenUserIsNotFoundByUsername(): void
    {
        $this->expectException(UserNotFoundException::class);

        $this->loginService->authenticate('tijmen', 'my-raw-password');
    }

    public function testItFailsAuthenticationWhenPasswordIsWrong(): void
    {
        $this->expectException(AuthenticationException::class);

        $this->userRepository->save(User::new(Uuid::uuid4(), 'tijmen', '123456'));

        $this->loginService->authenticate('tijmen', 'wrong-password');
    }

    public function testItAuthenticatesAUserWithACorrectPassword(): void
    {
        $this->userRepository->save(User::new(Uuid::uuid4(), 'tijmen', '123456'));

        $token = $this->loginService->authenticate('tijmen', '123456');

        static::assertIsString($token); // Pretty useless assertion but implicates the token is returned
    }

    public function testItRehashesThePasswordWhenNecessary(): void
    {
        $user = User::new(Uuid::uuid4(), 'tijmen', 'rehash');

        $this->userRepository->save($user);

        $token = $this->loginService->authenticate('tijmen', 'rehash');

        static::assertIsString($token); // Pretty useless assertion but implicates the token is returned
        static::assertEquals('hashed-password', $user->getPassword());
    }
}
