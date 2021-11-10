<?php

declare(strict_types=1);

namespace App\Tests\Func\Category\Utils;

trait TearDownCategory
{
    /* This function tearDown interact directly the database via doctrine */
    protected function tearDown(): void
    {
        $this->categoryManager->deleteOne($this->category->getId());
        $this->userManager->deleteOne($this->user->getId());
    }
}
