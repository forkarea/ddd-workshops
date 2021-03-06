<?php
declare(strict_types=1);

namespace TSwiackiewicz\AwesomeApp\DomainModel\User;

use TSwiackiewicz\AwesomeApp\SharedKernel\User\Exception\InvalidArgumentException;

/**
 * Example of Value Object, very convenient way for data validation
 * In this particular case, user login validation can be implemented
 * with command validator
 *
 * @package TSwiackiewicz\AwesomeApp\DomainModel\User
 */
class UserLogin
{
    /**
     * @var string
     */
    private $login;

    /**
     * UserLogin constructor.
     * @param string $login
     * @throws InvalidArgumentException
     */
    public function __construct(string $login)
    {
        if (false === filter_var($login, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Login should be a valid email address');
        }

        $this->login = $login;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->login;
    }
}