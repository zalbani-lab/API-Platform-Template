<?php

declare(strict_types=1);

namespace App\Tests\EntityManager\Entity\Media;

use App\Entity\Media;
use App\Tests\EntityManager\TestEntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Provider\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaManager implements TestEntityManagerInterface
{
    private ObjectManager $objectManager;
    private \Faker\Generator $faker;
    private string $mediaPayload = '{"name": "%s"}';

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        $this->faker = Factory::create();
    }

    public function deleteOne(int $id): void
    {
        $mediaToDelete = $this->getOne($id);
        if (null !== $mediaToDelete && $mediaToDelete instanceof Media) {
            $this->objectManager->remove($mediaToDelete);
            $this->objectManager->flush();
            $this->objectManager->clear();
        }
    }

    public function getOne(int $id): ?Media
    {
        $result = $this->objectManager
            ->getRepository(Media::class)
            ->find($id);
        if (null !== $result && $result instanceof Media) {
            return $result;
        }

        return null;
    }

    public function createOne(?array $options = null): Media
    {
        $mediaTemp = new Media();

        $mediaTemp->setTitle($this->faker->text(20))
        ->setLegend($this->faker->text(20))
        ->setTarget($this->faker->text(20))
        ->setFile($this->getFile());

        $this->objectManager->persist($mediaTemp);
        $this->objectManager->flush();
        $this->objectManager->clear();

        return $mediaTemp;
    }

    public function getRandomPayload(?string $option = null): string
    {
        return sprintf($this->mediaPayload, $this->faker->text(20));
    }

    private function getFile(): UploadedFile
    {
        $imageName = $this->faker->firstName;

        return new UploadedFile(
            $this->generateRandomImage($imageName),
            $imageName.'.jpeg',
            'jpeg',
            null,
            true
        );
    }

    private function generateRandomImage(string $imageName = 'test', int $width = 640, int $height = 480): ?string
    {
        $absolutePath = __DIR__.'/tempImageContainer/'.$imageName.'.jpeg';
        // Create a blank image:
        $im = imagecreatetruecolor($width, $height);
        // Add light background color:
        $bgColor = imagecolorallocate($im, rand(100, 255), rand(100, 255), rand(100, 255));
        imagefill($im, 0, 0, $bgColor);

        // Save the image:
        $isGenerated = imagejpeg($im, $absolutePath);

        // Free up memory:
        imagedestroy($im);

        if ($isGenerated) {
            return $absolutePath;
        } else {
            return null;
        }
    }
}
