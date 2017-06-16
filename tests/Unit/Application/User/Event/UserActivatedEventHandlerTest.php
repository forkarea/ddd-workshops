<?php
declare(strict_types=1);

namespace TSwiackiewicz\AwesomeApp\Tests\Unit\Application\User\Event;

use TSwiackiewicz\AwesomeApp\Application\User\Event\UserActivatedEventHandler;
use TSwiackiewicz\AwesomeApp\SharedKernel\User\Exception\RuntimeException;
use TSwiackiewicz\AwesomeApp\Tests\Unit\UserBaseTestCase;

/**
 * Class UserActivatedEventHandlerTest
 * @package TSwiackiewicz\AwesomeApp\Tests\Unit\Application\User\Event
 *
 * @coversDefaultClass UserActivatedEventHandler
 */
class UserActivatedEventHandlerTest extends UserBaseTestCase
{
    /**
     * @test
     */
    public function shouldFailWhenHandledEventIsInvalid(): void
    {
        $this->expectException(RuntimeException::class);

        $handler = new UserActivatedEventHandler(
            $this->getEventStoreMock(),
            $this->getUserRepositoryMock(),
            $this->getUserNotifierMock()
        );
        $handler->handle(FakeUserEvent::create());
    }
}