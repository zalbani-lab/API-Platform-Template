<?php

declare(strict_types=1);

namespace App\DataFixtures\DependenciesFixtures;

use App\Entity\Media;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Storage\StorageInterface;

class MediaFixtures extends Fixture
{
    const IMAGE_NAME = [
        'FIXTURE_0',
        'FIXTURE_1',
        'FIXTURE_2',
        'FIXTURE_3',
        'FIXTURE_4',
        'FIXTURE_5',
        'FIXTURE_6',
        'FIXTURE_7',
        'FIXTURE_8',
        'FIXTURE_9',
        'FIXTURE_10',
    ];
    private \Faker\Generator $faker;
    private StorageInterface $storage;
    private ObjectManager $manager;

    public function __construct(StorageInterface $storage)
    {
        $this->faker = Factory::create();
        $this->storage = $storage;
    }

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        foreach (self::IMAGE_NAME as $image) {
            $this->createMedia($image);
        }
    }

    public function createMedia(string $imageName): Media
    {
        $media = new Media();

        $media->setTitle($imageName)
            ->setLegend($this->faker->text(20))
            ->setTarget('fixture')
            ->setFile($this->getRandomImage());
        $this->manager->persist($media);
        $this->manager->flush();

        // Temporary fix to resolve contentUrl
        // Because DataFixture don't trigger event : ResolveMediaContentUrlSubsciber
        $contentUrl = 'http://127.0.0.1:8000'.$this->storage->resolveUri($media, 'file');
        $media->setContentUrl($contentUrl);
        $this->manager->persist($media);
        $this->manager->flush();

        return $media;
    }

    private function getRandomImage(): UploadedFile
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
        $absolutePath = __DIR__.$imageName.'.jpeg';
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
