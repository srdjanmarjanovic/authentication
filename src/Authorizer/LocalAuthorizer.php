<?php

/*
 * This file is part of the Active Collab Authentication project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

namespace ActiveCollab\Authentication\Authorizer;

use ActiveCollab\Authentication\AuthenticatedUser\RepositoryInterface;
use ActiveCollab\Authentication\Exception\InvalidAuthenticationRequestException;
use ActiveCollab\Authentication\Exception\UserNotFoundException;
use ActiveCollab\Authentication\Exception\InvalidPasswordException;

/**
 * @package ActiveCollab\Authentication\Authorizer
 */
class LocalAuthorizer implements AuthorizerInterface
{
    /**
     * @var RepositoryInterface
     */
    private $user_repository;

    /**
     * @param RepositoryInterface $user_repository
     */
    public function __construct(RepositoryInterface $user_repository)
    {
        $this->user_repository = $user_repository;
    }

    /**
     * Credentials should be in array format with keys: token and username.
     * Example: ['username' => 'john.doe.123@gmail.com', 'password' => '123abc'].
     *
     * {@inheritdoc}
     */
    public function verifyCredentials(array $credentials)
    {
        if ($this->isEmpty($credentials, 'username') || $this->isEmpty($credentials, 'password')) {
            throw new InvalidAuthenticationRequestException();
        }

        $user = $this->user_repository->findByUsername($credentials['username']);

        if (!$user) {
            throw new UserNotFoundException();
        }

        if (!$user->isValidPassword($credentials['password'])) {
            throw new InvalidPasswordException();
        }

        if (!$user->canAuthenticate()) {
            throw new UserNotFoundException();
        }

        return ['is_error' => false, 'payload' => $user];
    }

    /**
     * {@inheritdoc}
     */
    public function onLogin(array $payload)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function onLogout(array $payload)
    {
    }

    /**
     * @param  array  $credentials
     * @param  string $field
     * @return bool
     */
    private function isEmpty(array $credentials, $field)
    {
        return isset($credentials[$field]) ? $credentials[$field] === '' : true;
    }
}
