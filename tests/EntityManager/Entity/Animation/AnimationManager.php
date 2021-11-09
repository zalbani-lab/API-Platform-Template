<?php

declare(strict_types=1);

namespace App\Tests\EntityManager\Entity\Animation;

use App\Entity\Animation;
use App\Entity\User;
use App\Tests\EntityManager\TestEntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AnimationManager implements TestEntityManagerInterface
{
    use AnimationFunction;

    private string $animationPayload = '{"title": "%s", "shortDescription": "%s", "longDescription": "%s"}';

    private ObjectManager $objectManager;
    private \Faker\Generator $faker;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        $this->faker = Factory::create();
    }

    public function deleteOne(int $id): void
    {
        $animationToDelete = $this->getOne($id);
        if (null !== $animationToDelete && $animationToDelete instanceof Animation) {
            $this->objectManager->remove($animationToDelete);
            $this->objectManager->flush();
            $this->objectManager->clear();
        }
    }

    public function getOne(int $id): ?Animation
    {
        $result = $this->objectManager
            ->getRepository(Animation::class)
            ->find($id);
        if (null !== $result && $result instanceof Animation) {
            return $result;
        }

        return null;
    }

    public function createOne(?array $options = null): Animation
    {
        $animationTemp = new Animation();

        $animationTemp->setTitle($this->getRandomTitle())
            ->setShortDescription($this->getRandomText(random_int(20, 50)))
            ->setLongDescription($this->getRandomText(random_int(80, 200)));

        $this->objectManager->persist($animationTemp);
        $this->objectManager->flush();
        $this->objectManager->clear();

        return $animationTemp;
    }

    public function getRandomPayload(?string $option = null): string
    {
        return sprintf($this->animationPayload, $this->getRandomTitle(), $this->getRandomText(random_int(20, 50)), $this->getRandomText(random_int(80, 200)));
    }
}
