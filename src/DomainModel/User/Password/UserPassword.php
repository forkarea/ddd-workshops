<?php
declare(strict_types=1);

namespace TSwiackiewicz\AwesomeApp\DomainModel\User\Password;

use TSwiackiewicz\AwesomeApp\SharedKernel\User\Exception\InvalidArgumentException;

/**
 * Class UserPassword
 * @package TSwiackiewicz\AwesomeApp\DomainModel\User\Password
 */
class UserPassword
{
    private const MIN_PASSWORD_LENGTH = 8;

    /**
     * @var string
     */
    private $password;

    /**
     * UserPassword constructor.
     * @param string $password
     * @throws InvalidArgumentException
     */
    public function __construct(string $password)
    {
        $this->assertPassword($password);

        $this->password = $password;
    }

    /**
     * @param string $password
     * @throws InvalidArgumentException
     */
    private function assertPassword(string $password): void
    {
        if (strlen($password) < self::MIN_PASSWORD_LENGTH) {
            throw new InvalidArgumentException('');
        }

        // TODO: other password assertion rules
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param UserPassword $password
     * @return bool
     */
    public function equals(UserPassword $password): bool
    {
        return $this->password === (string)$password;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->password;
    }
}