<?php

namespace ActiveCollab\Authentication\Test\Token;

use ActiveCollab\Authentication\AuthenticatedUser\AuthenticatedUserInterface;
use ActiveCollab\Authentication\Token\RepositoryInterface;
use ActiveCollab\Authentication\Token\TokenInterface;

/**
 * @package ActiveCollab\Authentication\Test\Token
 */
class Repository implements RepositoryInterface
{
    /**
     * @var array
     */
    private $tokens;

    /**
     * @param array $tokens
     */
    public function __construct(array $tokens = [])
    {
        $this->tokens = $tokens;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($token_id)
    {
        return isset($this->tokens[$token_id]) ? $this->tokens[$token_id] : null;
    }

    /**
     * @var array
     */
    private $used_tokens = [];

    /**
     * Return number of times that a token with the give ID was used
     *
     * @param  string  $token_id
     * @return integer
     */
    public function getUsageById($token_id)
    {
        return empty($this->used_tokens[$token_id]) ? 0 : $this->used_tokens[$token_id];
    }

    /**
     * Record that token with the given ID was used
     *
     * @param string $token_or_token_id
     */
    public function recordUsage($token_or_token_id)
    {
        $token_id = $token_or_token_id instanceof TokenInterface ? $token_or_token_id->getTokenid() : $token_or_token_id;

        if (empty($this->used_tokens[$token_id])) {
            $this->used_tokens[$token_id] = 0;
        }

        $this->used_tokens[$token_id]++;
    }

    /**
     * Issue a new token
     *
     * @param  AuthenticatedUserInterface $user
     * @param  \DateTimeInterface|null    $expires_at
     * @return TokenInterface
     */
    public function issueToken(AuthenticatedUserInterface $user, \DateTimeInterface $expires_at = null)
    {
        $token = isset($this->tokens[$user->getEmail()]) ? $this->tokens[$user->getEmail()] : sha1(time());

        return new Token($token, $user->getUsername(), $expires_at);
    }
}
