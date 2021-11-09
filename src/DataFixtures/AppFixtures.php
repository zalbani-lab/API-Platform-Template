<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\DataFixtures\DependenciesFixtures\MediaFixtures;
use App\Entity\Animation;
use App\Entity\Media;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    private UserPasswordEncoderInterface $encoder;
    private \Faker\Generator $faker;
    private ObjectManager $manager;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
        $this->faker = Factory::create();
    }

    public function getDependencies(): array
    {
        return [
            MediaFixtures::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $this->createUsersWithAnimations(10);

        $this->manager->flush();
    }

    private function createUsersWithAnimations(int $userNumber): void
    {
        for ($i = 0; $i < $userNumber; ++$i) {
            $user = new User();
            $user->setEmail($this->faker->email)
                ->setPassword($this->hashPassword($user, 'password'));
            $this->manager->persist($user);

            $this->createAnimations($user, random_int(5, 15));
        }
    }

    private function createAnimations(User $userAuthor, int $animationNumber): void
    {
        for ($j = 0; $j < $animationNumber; ++$j) {
            $Animation = (new Animation())->setAuthor($userAuthor)
                ->setImage($this->getARandomMedia())
                ->setTitle($this->faker->text(20))
                ->setShortDescription($this->faker->realText(100))
                ->setLongDescription($this->faker->realText(300));

            $this->manager->persist($Animation);
        }
    }


    private function hashPassword(User $user, string $password): string
    {
        return $this->encoder->encodePassword($user, $password);
    }

    private function getARandomMedia(): ?Media
    {
        return $this->manager->getRepository(Media::class)
            ->findOneByTitle(MediaFixtures::IMAGE_NAME[random_int(0, (count(MediaFixtures::IMAGE_NAME) - 1))]);
    }
}
