<?php

declare(strict_types=1);

namespace App\Tests\Func\Media\Utils;

trait TearDownMedia
{
    /* This function tearDown interact directly the database via doctrine */
    protected function tearDown(): void
    {
        $this->mediaManager->deleteOne($this->media->getId());
        $this->userManager->deleteOne($this->user->getId());
    }
}
