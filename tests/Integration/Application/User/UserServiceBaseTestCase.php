<?php
declare(strict_types=1);

namespace TSwiackiewicz\AwesomeApp\Tests\Integration\Application\User;

use PHPUnit\Framework\TestCase;
use TSwiackiewicz\AwesomeApp\Application\User\{
    Event\UserDisabledEventHandler, Event\UserEnabledEventHandler, Event\UserPasswordChangedEventHandler, Event\UserRegisteredEventHandler, Event\UserUnregisteredEventHandler, UserService
};
use TSwiackiewicz\AwesomeApp\Application\User\Command\{
    ActivateUserCommand, ChangePasswordCommand, DisableUserCommand, EnableUserCommand, RegisterUserCommand, UnregisterUserCommand
};
use TSwiackiewicz\AwesomeApp\DomainModel\User\{
    Exception\PasswordException, Exception\UserAlreadyExistsException, Exception\UserNotFoundException, Password\UserPassword, Password\UserPasswordService, User, UserLogin
};
use TSwiackiewicz\AwesomeApp\DomainModel\User\Event\{
    UserDisabledEvent, UserEnabledEvent, UserPasswordChangedEvent, UserRegisteredEvent, UserUnregisteredEvent
};
use TSwiackiewicz\AwesomeApp\Infrastructure\{
    InMemoryStorage, User\InMemoryUserReadModelRepository, User\InMemoryUserRepository, User\StdOutUserNotifier
};
use TSwiackiewicz\AwesomeApp\SharedKernel\User\Exception\InvalidArgumentException;
use TSwiackiewicz\AwesomeApp\SharedKernel\User\UserId;
use TSwiackiewicz\DDD\Event\EventBus;

/**
 * Class UserServiceBaseTestCase
 * @package TSwiackiewicz\AwesomeApp\Tests\Integration\Application\User
 */
abstract class UserServiceBaseTestCase extends TestCase
{
    /**
     * @var int
     */
    protected $userId = 1;

    /**
     * @var string
     */
    protected $login = 'test@domain.com';

    /**
     * @var string
     */
    protected $password = 'password1234';

    /**
     * @var string
     */
    protected $hash = '94b3e2c871ff1b3e4e03c74cd9c501f5';

    /**
     * @var UserService
     */
    protected $service;

    /**
     * Disable user
     */
    protected function disableUser(): void
    {
        $repository = new InMemoryUserRepository();
        $repository->save(
            new User(
                UserId::fromInt($this->userId),
                new UserLogin($this->login),
                new UserPassword($this->password),
                true,
                false
            )
        );
    }

    /**
     * Enable user
     */
    protected function enableUser(): void
    {
        $repository = new InMemoryUserRepository();
        $repository->save(
            new User(
                UserId::fromInt($this->userId),
                new UserLogin($this->login),
                new UserPassword($this->password),
                true,
                true
            )
        );
    }

    /**
     * Setup fixtures
     */
    protected function setUp(): void
    {
        $this->registerEventHandlers();

        InMemoryStorage::clear();
        $identityMap = new \ReflectionProperty(InMemoryUserRepository::class, 'identityMap');
        $identityMap->setAccessible(true);
        $identityMap->setValue(null, []);

        $repository = new InMemoryUserRepository();
        $repository->save(
            new User(
                UserId::fromInt($this->userId),
                new UserLogin($this->login),
                new UserPassword($this->password),
                false,
                false
            )
        );

        $this->service = new UserService(
            $repository,
            new UserPasswordService()
        );
    }

    private function registerEventHandlers(): void
    {
        EventBus::subscribe(
            UserRegisteredEvent::class,
            new UserRegisteredEventHandler(
                new StdOutUserNotifier()
            )
        );
        EventBus::subscribe(
            UserEnabledEvent::class,
            new UserEnabledEventHandler(
                new StdOutUserNotifier()
            )
        );
        EventBus::subscribe(
            UserDisabledEvent::class,
            new UserDisabledEventHandler(
                new StdOutUserNotifier()
            )
        );
        EventBus::subscribe(
            UserUnregisteredEvent::class,
            new UserUnregisteredEventHandler(
                new StdOutUserNotifier()
            )
        );
        EventBus::subscribe(
            UserPasswordChangedEvent::class,
            new UserPasswordChangedEventHandler(
                new StdOutUserNotifier()
            )
        );
    }
}