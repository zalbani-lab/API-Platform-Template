<?php

declare(strict_types=1);

namespace App\Tests\EntityManager\Entity\User;

use App\Entity\User;

// @todo: Refactor user function for tests

trait UserFunctions
{
    public function getRandomEmail(): string
    {
        return $this->faker->email;
    }

    public function getRandomPassword(bool $withOutSpecialCharacter = true): string
    {
        if (true === $withOutSpecialCharacter) {
            return $this->removeSpecialCharacter($this->faker->password);
        } else {
            return $this->faker->password;
        }
    }

    public function getCustomPayload(string $email, string $password): string
    {
        return sprintf($this->userPayload, $email, $password);
    }

    public function getLoginInformation(string $email, string $password): array
    {
        return ['email' => $email, 'password' => $password];
    }

    private function removeSpecialCharacter(string $passwordGeneratedByFaker): string
    {
        return preg_replace('/[^A-Za-z0-9\-]/', '', $passwordGeneratedByFaker); // Removes special chars.
    }

    private function hashPassword(User $user, string $password): string
    {
        return $this->passwordEncoder->encodePassword($user, $password);
    }
}
