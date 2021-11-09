<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Entity\Animation;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class AnimationTest extends TestCase
{
    private Animation $animation;

    protected function setUp(): void
    {
        parent::setUp();

        $this->animation = new Animation();
    }

    /**
     * @group unit
     * @group unitAnimation
     */
    public function testGetTitle(): void
    {
        $value = 'My title';
        $response = $this->animation->setTitle($value);

        self::assertInstanceOf(Animation::class, $response);
        self::assertEquals($value, $this->animation->getTitle());
    }

    /**
     * @group unit
     * @group unitAnimation
     */
    public function testGetShortDescription(): void
    {
        $value = 'My short description here';
        $response = $this->animation->setShortDescription($value);

        self::assertInstanceOf(Animation::class, $response);
        self::assertEquals($value, $this->animation->getShortDescription());
    }

    /**
     * @group unit
     * @group unitAnimation
     */
    public function testGetLongDescription(): void
    {
        $value = 'My long description here';
        $response = $this->animation->setLongDescription($value);

        self::assertInstanceOf(Animation::class, $response);
        self::assertEquals($value, $this->animation->getLongDescription());
    }

    /**
    * @group unit
    * @group unitAnimation
    */
    public function testGetDateStart(): void
    {
        $value = new \DateTimeImmutable();
        $response = $this->animation->setDateStart($value);

        self::assertInstanceOf(Animation::class, $response);
        self::assertEquals($value, $this->animation->getDateStart());
    }

    /**
     * @group unit
     * @group unitAnimation
     */
    public function testGetDateEnd(): void
    {
        $value = new \DateTimeImmutable();
        $response = $this->animation->setDateEnd($value);

        self::assertInstanceOf(Animation::class, $response);
        self::assertEquals($value, $this->animation->getDateEnd());
    }

    /**
    * @group unit
    * @group unitAnimation
    */
    public function testAddAndDeleteUsersIntoAnAnimation(): void
    {
        $value = new User();

        $response = $this->animation->addContributor($value);

        self::assertInstanceOf(Animation::class, $response);
        self::assertCount(1, $this->animation->getContributors());
        self::assertTrue($this->animation->getContributors()->contains($value));

        $response = $this->animation->removeContributor($value);
        self::assertInstanceOf(Animation::class, $response);
        self::assertCount(0, $this->animation->getContributors());
        self::assertFalse($this->animation->getContributors()->contains($value));
    }
}
