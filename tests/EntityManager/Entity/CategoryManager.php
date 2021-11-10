<?php

declare(strict_types=1);

namespace App\Tests\EntityManager\Entity;

use App\Entity\Category;
use App\Tests\EntityManager\TestEntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategoryManager implements TestEntityManagerInterface
{
    private string $categoryPayload = '{"name": "%s"}';

    private ObjectManager $objectManager;
    private \Faker\Generator $faker;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        $this->faker = Factory::create();
    }

    public function deleteOne(int $id): void
    {
        $categoryToDelete = $this->getOne($id);
        if (null !== $categoryToDelete && $categoryToDelete instanceof Category) {
            $this->objectManager->remove($categoryToDelete);
            $this->objectManager->flush();
            $this->objectManager->clear();
        }
    }

    public function getOne(int $id): ?Category
    {
        $result = $this->objectManager
            ->getRepository(Category::class)
            ->find($id);
        if (null !== $result && $result instanceof Category) {
            return $result;
        }

        return null;
    }

    public function createOne(?array $options = null): Category
    {
        $categoryTemp = new Category();

        $categoryTemp->setName($this->faker->lastName);

        $this->objectManager->persist($categoryTemp);
        $this->objectManager->flush();
        $this->objectManager->clear();

        return $categoryTemp;
    }

    public function getRandomPayload(?string $option = null): string
    {
        return sprintf($this->categoryPayload, $this->faker->lastName);
    }
}
