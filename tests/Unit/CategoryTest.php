<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Entity\Animation;
use App\Entity\Category;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = new Category();
    }

    /**
     * @group unit
     * @group unitCategory
     */
    public function testGetName(): void
    {
        $value = 'Random name';
        $response = $this->category->setName($value);

        self::assertInstanceOf(Category::class, $response);
        self::assertEquals($value, $this->category->getName());
    }

    /**
    * @group unit
    * @group unitCategory
    */
    public function testAddAndDeleteAnimationIntoAnCategory(): void
    {
        $value = new Animation();

        $response = $this->category->addAnimation($value);

        self::assertInstanceOf(Category::class, $response);
        self::assertCount(1, $this->category->getAnimations());
        self::assertTrue($this->category->getAnimations()->contains($value));

        $response = $this->category->removeAnimation($value);
        self::assertInstanceOf(Category::class, $response);
        self::assertCount(0, $this->category->getAnimations());
        self::assertFalse($this->category->getAnimations()->contains($value));
    }
}
