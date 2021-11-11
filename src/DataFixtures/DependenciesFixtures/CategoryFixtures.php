<?php

declare(strict_types=1);

namespace App\DataFixtures\DependenciesFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    const CATEGORIES = [
        'Arts',
        'Arts vivants',
        'Culture numérique',
        'Histoire',
        'Ateliers et jeux',
        'Littérature',
        'Lyon et région',
        'Société',
        'Musique',
        'Patrimoine',
        'Sciences et santé',
        'Monde'
    ];

    private ObjectManager $manager;

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $this->createCategory();

        $this->manager->flush();
    }

    private function createCategory(): void
    {
        foreach (self::CATEGORIES as $categoryName) {
            $tempCategory = new Category();
            $tempCategory->setName($categoryName);
            $this->manager->persist($tempCategory);
            $this->manager->flush();
        }
    }
}
