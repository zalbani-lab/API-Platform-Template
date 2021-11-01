<?php

declare(strict_types=1);

namespace App\Tests\EntityManager\Entity;

use App\Entity\Log;
use App\Tests\EntityManager\TestEntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class LogManager implements TestEntityManagerInterface
{
    private ObjectManager $objectManager;
    private \Faker\Generator $faker;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        $this->faker = Factory::create();
    }

    public function deleteOne(int $id): void
    {
        $logToDelete = $this->getOne($id);
        if (null !== $logToDelete && $logToDelete instanceof Log) {
            $this->objectManager->remove($logToDelete);
            $this->objectManager->flush();
            $this->objectManager->clear();
        }
    }

    public function getOne(int $id): ?Log
    {
        $result = $this->objectManager
            ->getRepository(Log::class)
            ->find($id);
        if (null !== $result && $result instanceof Log) {
            return $result;
        }

        return null;
    }

    public function createOne(?array $options = null): Log
    {
        $logTemp = new Log();
        $randomArray = [];
        for ($i = 0; $i > random_int(5, 20); ++$i) {
            array_push($randomArray, $this->faker->title);
        }

        $logTemp->setRequest($this->faker->shuffleArray($randomArray))
            ->setResponse($this->faker->shuffleArray($randomArray))
            ->setAuthor(random_int(12, 40))
            ->setMethod($this->faker->title)
            ->setTargetElement($this->faker->title)
            ->setTargetId(random_int(20, 100))
            ->setLevel(random_int(0, 5));

        $this->objectManager->persist($logTemp);
        $this->objectManager->flush();
        $this->objectManager->clear();

        return $logTemp;
    }

    public function getRandomPayload(?string $option = null): string
    {
        return '';
    }
}
