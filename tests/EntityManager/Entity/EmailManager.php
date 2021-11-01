<?php

declare(strict_types=1);

namespace App\Tests\EntityManager\Entity;

use App\Entity\Email;
use App\Tests\EntityManager\TestEntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EmailManager implements TestEntityManagerInterface
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
        $emailToDelete = $this->getOne($id);
        if (null !== $emailToDelete && $emailToDelete instanceof Email) {
            $this->objectManager->remove($emailToDelete);
            $this->objectManager->flush();
            $this->objectManager->clear();
        }
    }

    public function getOne(int $id): ?Email
    {
        $result = $this->objectManager
            ->getRepository(Email::class)
            ->find($id);
        if (null !== $result && $result instanceof Email) {
            return $result;
        }

        return null;
    }

    public function createOne(?array $options = null): Email
    {
        $emailTemp = new Email();

        $emailTemp->setAuthor($this->faker->randomDigit)
            ->setSubject($this->faker->title)
            ->setRecipient($this->faker->email)
            ->setTemplate($this->faker->text(20))
            ->setContent($this->faker->text)
            ->setContext('Email created during test');

        $this->objectManager->persist($emailTemp);
        $this->objectManager->flush();
        $this->objectManager->clear();

        return $emailTemp;
    }

    public function getRandomPayload(?string $option = null): string
    {
        return '';
    }
}
