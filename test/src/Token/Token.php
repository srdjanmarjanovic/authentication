<?php

namespace ActiveCollab\Authentication\Test\Token;

use ActiveCollab\Authentication\Token\TokenInterface;
use ActiveCollab\Authentication\AuthenticationResultInterface\Implementation as AuthenticationResultInterfaceImplementation;

/**
 * @package ActiveCollab\Authentication\Test\Token
 */
class Token implements TokenInterface
{
    use AuthenticationResultInterfaceImplementation;

    /**
     * @var string
     */
    private $token;

    /**
     * @var \DateTimeInterface
     */
    private $expires_at;

    /**
     * @param string                  $token
     * @param \DateTimeInterface|null $expires_at
     */
    public function __construct($token, \DateTimeInterface $expires_at = null)
    {
        $this->token = $token;
        $this->expires_at = $expires_at;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'token' => $this->token,
            'expires_at' => $this->expires_at instanceof \JsonSerializable ? $this->expires_at : $this->expires_at->getTimestamp(),
        ];
    }
}