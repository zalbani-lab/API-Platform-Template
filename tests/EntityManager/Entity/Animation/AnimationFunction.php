<?php

declare(strict_types=1);

namespace App\Tests\EntityManager\Entity\Animation;

trait AnimationFunction
{
    private function getRandomTitle(): string
    {
        return $this->faker->title;
    }

    private function getRandomText(int $length): string
    {
        return $this->faker->text($length);
    }
}
