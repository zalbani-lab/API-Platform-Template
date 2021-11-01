<?php

declare(strict_types=1);

namespace App\Tests\Func\Animation;

/* Trait importation */

use App\Tests\Func\AbstractEndPoint;
use App\Tests\Func\Animation\Utils\SetUpAnimation;
use App\Tests\Func\Animation\Utils\TearDownAnimation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CrudTest extends AbstractEndPoint
{
    use SetUpAnimation;
    use TearDownAnimation;

    /**
     * @group func
     * @group funcAnimation
     * @group crudAnimation
     */
    public function testGetAnimations(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_GET,
            '/api/animations'
        );
        $responseContent = $response->getContent();

        $responseDecoded = json_decode($responseContent);
        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    /**
     * @group func
     * @group funcAnimation
     * @group crudAnimation
     */
    public function testPostAnimation(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_POST,
            '/api/animations',
            $this->randomPayload,
            [],
            true,
            $this->authorLoginCredential
        );
        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent, true);

        self::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);

        /* Delete the Animation previously created */
        $this->animationManager->deleteOne($responseDecoded['id']);
    }

    /**
     * @group func
     * @group funcAnimation
     * @group crudAnimation
     */
    public function testGetOneAnimation(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_GET,
            '/api/animations/'.$this->animation->getId(),
            '',
            [],
            false
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    /**
     * @group func
     * @group funcAnimation
     * @group crudAnimation
     */
    public function testPatchAnimation(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_PATCH,
            '/api/animations/'.$this->animation->getId(),
            $this->randomPayload,
            [],
            true,
            $this->authorLoginCredential
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent, true);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    /**
     * @group func
     * @group funcAnimation
     * @group crudAnimation
     */
    public function testPutAnimation(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_PUT,
            '/api/animations/'.$this->animation->getId(),
            $this->randomPayload,
            [],
            true,
            $this->authorLoginCredential
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent, true);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    /**
     * @group func
     * @group funcAnimation
     * @group crudAnimation
     */
    public function testDeleteAnimation(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_DELETE,
            '/api/animations/'.$this->animation->getId(),
            '',
            [],
            true,
            $this->authorLoginCredential
        );
        self::assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
