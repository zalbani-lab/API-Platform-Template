<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Entity\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    private Email $email;

    protected function setUp(): void
    {
        parent::setUp();
        $this->email = new Email();
    }

    /**
     * @group unit
     * @group unitEmail
     */
    public function testGetAuthor(): void
    {
        $value = 0;
        $response = $this->email->setAuthor($value);

        self::assertInstanceOf(Email::class, $response);
        self::assertEquals($value, $this->email->getAuthor());
    }

    /**
     * @group unit
     * @group unitEmail
     */
    public function testGetRecipient(): void
    {
        $value = 'email@mail.com';
        $response = $this->email->setRecipient($value);

        self::assertInstanceOf(Email::class, $response);
        self::assertEquals($value, $this->email->getRecipient());
    }

    /**
     * @group unit
     * @group unitEmail
     */
    public function testGetSubject(): void
    {
        $value = 'subject';
        $response = $this->email->setSubject($value);

        self::assertInstanceOf(Email::class, $response);
        self::assertEquals($value, $this->email->getSubject());
    }

    /**
     * @group unit
     * @group unitEmail
     */
    public function testGetTemplate(): void
    {
        $value = 'template';
        $response = $this->email->setTemplate($value);

        self::assertInstanceOf(Email::class, $response);
        self::assertEquals($value, $this->email->getTemplate());
    }

    /**
     * @group unit
     * @group unitEmail
     */
    public function testGetContent(): void
    {
        $value = 'content';
        $response = $this->email->setContent($value);

        self::assertInstanceOf(Email::class, $response);
        self::assertEquals($value, $this->email->getContent());
    }

    /**
     * @group unit
     * @group unitEmail
     */
    public function testGetContext(): void
    {
        $value = 'context';
        $response = $this->email->setContext($value);

        self::assertInstanceOf(Email::class, $response);
        self::assertEquals($value, $this->email->getContext());
    }
}
