<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Entity\Animation;
use App\Entity\User;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = new User();
    }

    /**
     * @group unit
     * @group unitUser
     */
    public function testGetEmail(): void
    {
        $value = 'test@test.fr';
        $response = $this->user->setEmail($value);

        self::assertInstanceOf(User::class, $response);
        self::assertEquals($value, $this->user->getEmail());
        self::assertEquals($value, $this->user->getUsername());
    }

    /**
     * @group unit
     * @group unitUser
     */
    public function testGetRoles(): void
    {
        $value = ['ROLE_ADMIN'];
        $response = $this->user->setRoles($value);

        self::assertInstanceOf(User::class, $response);
        self::assertContains('ROLE_USER', $this->user->getRoles());
        self::assertContains('ROLE_ADMIN', $this->user->getRoles());
    }

    /**
     * @group unit
     * @group unitUser
     */
    public function testGetPassword(): void
    {
        $value = 'password';
        $response = $this->user->setPassword($value);

        self::assertInstanceOf(User::class, $response);
        self::assertStringContainsString($value, $this->user->getPassword());
    }

    /**
     * @group unit
     * @group unitUser
     */
    public function testGetMultipleAnimations(): void
    {
        $nb_occur = random_int(3, 12);
        for ($i = 0; $i < $nb_occur; ++$i) {
            $response = $this->user->addAnimation($this->createFakeAnimation());
        }

        self::assertInstanceOf(User::class, $response);
        self::assertCount($nb_occur, $this->user->getAnimations());
    }

    /**
     * @group unit
     * @group unitUser
     */
    public function testAddAndDeleteAnAnimationsToAUser(): void
    {
        $value = $this->createFakeAnimation();

        $response = $this->user->addAnimation($value);

        self::assertInstanceOf(User::class, $response);
        self::assertCount(1, $this->user->getAnimations());
        self::assertTrue($this->user->getAnimations()->contains($value));

        $response = $this->user->removeAnimation($value);
        self::assertInstanceOf(User::class, $response);
        self::assertCount(0, $this->user->getAnimations());
        self::assertFalse($this->user->getAnimations()->contains($value));
    }

    private function createFakeAnimation(): Animation
    {
        $faker = Factory::create();
        $Animation = new Animation();
        $Animation->setTitle($faker->title)
            ->setShortDescription($faker->text(100))
            ->setLongDescription($faker->text(300));

        return $Animation;
    }
}
