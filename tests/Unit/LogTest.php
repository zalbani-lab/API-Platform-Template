<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Entity\Log;
use PHPUnit\Framework\TestCase;

class LogTest extends TestCase
{
    private Log $log;

    protected function setUp(): void
    {
        parent::setUp();
        $this->log = new Log();
    }

    /**
     * @group unit
     * @group unitLog
     */
    public function testGetAuthor(): void
    {
        $value = 0;
        $response = $this->log->setAuthor($value);

        self::assertInstanceOf(log::class, $response);
        self::assertEquals($value, $this->log->getAuthor());
    }

    /**
     * @group unit
     * @group unitLog
     */
    public function testGetMethod(): void
    {
        $value = 'method test';
        $response = $this->log->setMethod($value);

        self::assertInstanceOf(log::class, $response);
        self::assertEquals($value, $this->log->getMethod());
    }

    /**
     * @group unit
     * @group unitLog
     */
    public function testGetTargetElement(): void
    {
        $value = 'target example';
        $response = $this->log->setTargetElement($value);

        self::assertInstanceOf(log::class, $response);
        self::assertEquals($value, $this->log->getTargetElement());
    }

    /**
     * @group unit
     * @group unitLog
     */
    public function testGetTargetId(): void
    {
        $value = 0;
        $response = $this->log->setTargetId($value);

        self::assertInstanceOf(log::class, $response);
        self::assertEquals($value, $this->log->getTargetId());
    }

    /**
     * @group unit
     * @group unitLog
     */
    public function testGetRequest(): void
    {
        $value = [];
        $response = $this->log->setRequest($value);

        self::assertInstanceOf(log::class, $response);
        self::assertEquals($value, $this->log->getRequest());
    }

    /**
     * @group unit
     * @group unitLog
     */
    public function testGetResponse(): void
    {
        $value = [];
        $response = $this->log->setResponse($value);

        self::assertInstanceOf(log::class, $response);
        self::assertEquals($value, $this->log->getResponse());
    }

    /**
     * @group unit
     * @group unitLog
     */
    public function testGetLevel(): void
    {
        $value = 0;
        $response = $this->log->setLevel($value);

        self::assertInstanceOf(log::class, $response);
        self::assertEquals($value, $this->log->getLevel());
    }
}
