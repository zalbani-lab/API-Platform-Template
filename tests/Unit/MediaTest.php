<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Entity\Media;
use PHPUnit\Framework\TestCase;

class MediaTest extends TestCase
{
    private Media $media;

    protected function setUp(): void
    {
        parent::setUp();
        $this->media = new Media();
    }

    /**
     * @group unit
     * @group unitMedia
     */
    public function testGetContentUrl(): void
    {
        $value = 'content';
        $response = $this->media->setContentUrl($value);

        self::assertInstanceOf(Media::class, $response);
        self::assertEquals($value, $this->media->getContentUrl());
    }

    /**
     * @group unit
     * @group unitMedia
     */
    public function testGetFilePath(): void
    {
        $value = '/myfile.png';
        $response = $this->media->setFilePath($value);

        self::assertInstanceOf(Media::class, $response);
        self::assertEquals($value, $this->media->getFilePath());
    }

    /**
     * @group unit
     * @group unitMedia
     */
    public function testGetTarget(): void
    {
        $value = 'target';
        $response = $this->media->setTarget($value);

        self::assertInstanceOf(Media::class, $response);
        self::assertEquals($value, $this->media->getTarget());
    }

    /**
     * @group unit
     * @group unitMedia
     */
    public function testGetTitle(): void
    {
        $value = 'title';
        $response = $this->media->setTitle($value);

        self::assertInstanceOf(Media::class, $response);
        self::assertEquals($value, $this->media->getTitle());
    }

    /**
     * @group unit
     * @group unitMedia
     */
    public function testGetLegend(): void
    {
        $value = 'legend';
        $response = $this->media->setLegend($value);

        self::assertInstanceOf(Media::class, $response);
        self::assertEquals($value, $this->media->getLegend());
    }
}
