<?php

declare(strict_types=1);

namespace App\Tests\Func\Log\Utils;

trait TearDownLog
{
    /* This function tearDown interact directly the database via doctrine */
    protected function tearDown(): void
    {
        $this->userManager->deleteOne($this->user->getId());
        $this->logManager->deleteOne($this->log->getId());
    }
}
