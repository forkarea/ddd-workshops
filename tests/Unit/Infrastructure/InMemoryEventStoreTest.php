<?php
declare(strict_types=1);

namespace TSwiackiewicz\AwesomeApp\Tests\Unit\Infrastructure;

use TSwiackiewicz\AwesomeApp\Infrastructure\InMemoryEventStore;
use TSwiackiewicz\AwesomeApp\Tests\Unit\UserBaseTestCase;
use TSwiackiewicz\DDD\AggregateId;
use TSwiackiewicz\DDD\Event\Event;

/**
 * Class InMemoryEventStoreTest
 * @package TSwiackiewicz\AwesomeApp\Tests\Unit\Infrastructure\User
 *
 * @@coversDefaultClass InMemoryEventStore
 */
class InMemoryEventStoreTest extends UserBaseTestCase
{
    /**
     * @var AggregateId
     */
    private $id;

    /**
     * @var Event[]
     */
    private $events;

    /**
     * @test
     */
    public function shouldAppendEvents(): void
    {
        $store = new InMemoryEventStore();

        /** @var Event $event */
        foreach ($this->events as $event) {
            $store->append($this->id, $event);
        }

        $events = $store->load($this->id);

        self::assertCount(count($this->events), $events);
    }

    /**
     * @test
     * @depends shouldAppendEvents
     */
    public function shouldLoadAppendedEvents(): void
    {
        $store = new InMemoryEventStore();

        $events = $store->load($this->id);
        self::assertCount(count($this->events), $events);
    }

    /**
     * @test
     * @depends shouldLoadAppendedEvents
     */
    public function shouldNotLoadEventsIfNotAppended(): void
    {
        $this->clearCache();

        $store = new InMemoryEventStore();
        $events = $store->load($this->id);

        self::assertEquals([], $events);
    }

    /**
     * Setup fixtures
     */
    protected function setUp(): void
    {
        $this->id = AggregateId::fromString('1ec7223b-cb08-46d0-9410-03d0b2b81d06')->setId(1);
        $this->events = array_fill(0, 10, new FakeDomainEvent($this->id));
    }
}