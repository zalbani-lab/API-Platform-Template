<?php

declare(strict_types=1);

namespace App\Tests\Func\Media;

/* @todo: Ecrire tous les tests fonctionel pour l'upload des medias etc cela implique une re-ecrite de l'abstract end-point afin qui prenne en paramettre en body du form-data */

use App\Tests\Func\AbstractEndPoint;
use App\Tests\Func\Media\Utils\SetUpMedia;
use App\Tests\Func\Media\Utils\TearDownMedia;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CrudTest extends AbstractEndPoint
{
    use SetUpMedia;
    use TearDownMedia;

    /**
     * @group func
     * @group funcMedia
     * @group crudMedia
     */
    public function testGetMedias(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_GET,
            '/api/media',
            '',
            [],
            true,
            $this->userLoginCredential
        );
        $responseContent = $response->getContent();

        $responseDecoded = json_decode($responseContent);
        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    /**
     * @group func
     * @group funcMedia
     * @group crudMedia
     */
    public function testGetOneMedias(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_GET,
            '/api/media/'.$this->media->getId(),
            '',
            [],
            true,
            $this->userLoginCredential
        );
        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    /**
     * @group func
     * @group funcMedia
     * @group crudMedia
     */
    public function testDeleteMedia(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_DELETE,
            '/api/media/'.$this->media->getId(),
            '',
            [],
            true,
            $this->userLoginCredential
        );
        self::assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
