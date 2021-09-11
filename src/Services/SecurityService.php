<?php

namespace App\Services;

use App\Entity\User;
use App\Exception\AuthenticationError;
use App\Exception\AuthorizationError;
use App\Exception\NotFoundException;
use App\Repository\UserRepository;
use App\Security\AuthenticationResult;
use App\Security\Authorization;
use App\Security\Identity;
use App\Security\Session;
use App\Security\SessionRepository;
use App\Security\UserAuthorization;
use App\Security\Value\IdentityType;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

final class SecurityService
{
    private $userRepository;
    private $passwordEncoder;
    private $session;
    private $sessionRepository;

    public function __construct(
        UserRepository $userRepository,
        PasswordEncoderInterface $passwordEncoder,
        SessionInterface $session,
        SessionRepository $sessionRepository
    )
    {
       $this->userRepository = $userRepository;
       $this->passwordEncoder = $passwordEncoder;
       $this->session = $session;
       $this->sessionRepository =$sessionRepository;
    }

    public function login(Authorization $authorization, string $email, string $password): AuthenticationResult
    {
        if (!$authorization->canLogin()) {
            throw new AuthorizationError('Not allowed to login');
        }

        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            throw new AuthenticationError('No user with this email found.');
        }

        /* @var $user User */
        if (!$this->checkPassword($user->getPassword(), $password, $user->getSalt())) {
            throw new AuthenticationError('Wrong password');
        }

        $identity = new Identity(IdentityType::user(), $user->getId());
        $authorization = $this->createUserAuthorization($identity);
        $session = $this->startSession($authorization);

        return new AuthenticationResult($authorization, $user, $session);
    }

    public function logout(Authorization $authorization, string $sessionId): void
    {
        if (!$authorization->canLogout()) {
            throw new AuthorizationError('Not allowed to logout');
        }

        $this->sessionRepository->delete($sessionId);
        $this->session->remove('sessionId');
    }

    public function resumeSession(string $sessionId): AuthenticationResult
    {
        try {
            $session = $this->sessionRepository->find($sessionId);
        } catch (NotFoundException $e) {
            $this->session->remove('sessionId');
            throw new AuthenticationError('Invalid session id.');
        }

        $user = $this->userRepository->find($session->getUserId());
        if (!$user) {
            throw new AuthenticationError("User with id {$session->getUserId()} doesn't exist.");
        }
        $authorization = $this->createUserAuthorization($session->getIdentity());

        if (!$authorization->canLogin()) {
            throw new AuthenticationError('User is not allowed to login.');
        }

        $this->sessionRepository->touch($sessionId);
        $this->session->set('sessionId', $sessionId);

        return new AuthenticationResult($authorization, $user, $session);
    }

    public function getUserBySession(Session $session): User
    {
        $currentUser = $this->userRepository->find($session->getUserId());
        if (!$currentUser) {
            throw new NotFoundException("When fetching user's session: does not exist.");
        }

        return $currentUser;
    }

    private function createUserAuthorization(Identity $identity): UserAuthorization
    {
        $user = $this->userRepository->find($identity->getUserId());

        return new UserAuthorization($user);
    }

    private function startSession(Authorization $authorization): Session
    {
        if (!$authorization->canLogin()) {
            throw new AuthorizationError('Not allowed to start session.');
        }

        $identity = $authorization->getIdentity();
        $id = $this->sessionRepository->create($identity->getUserId());
        $this->session->set('sessionId', $id);

        return new Session(
            $id,
            $identity->getUserId(),
            new DateTimeImmutable()
        );
    }

    private function checkPassword(string $encodedPassword, string $rawPassword, string $salt = null): bool
    {
        return $this->passwordEncoder->isPasswordValid($encodedPassword, $rawPassword, $salt);
    }
}
