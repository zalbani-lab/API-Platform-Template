<?php

declare(strict_types=1);

namespace App\Tests\Func\Email\Utils;

trait TearDownEmail
{
    /* This function tearDown interact directly the database via doctrine */
    protected function tearDown(): void
    {
        $this->emailManager->deleteOne($this->email->getId());
        $this->userManager->deleteOne($this->user->getId());
    }
}
