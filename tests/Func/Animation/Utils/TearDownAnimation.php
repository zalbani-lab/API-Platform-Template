<?php

declare(strict_types=1);

namespace App\Tests\Func\Animation\Utils;

trait TearDownAnimation
{
    /* This function tearDown interact directly the database via doctrine */
    protected function tearDown(): void
    {
        $this->animationManager->deleteOne($this->animation->getId());
        $this->userManager->deleteOne($this->author->getId());
    }
}
