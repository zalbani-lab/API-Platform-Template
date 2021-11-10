<?php

declare(strict_types=1);

namespace App\Tests\Func\Category;

use App\Tests\Func\AbstractEndPoint;
use App\Tests\Func\Category\Utils\SetUpCategory;
use App\Tests\Func\Category\Utils\TearDownCategory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CrudTest extends AbstractEndPoint
{
    use SetUpCategory;
    use TearDownCategory;

    /**
     * @group func
     * @group funcCategory
     * @group crudCategory
     */
    public function testGetCategories(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_GET,
            '/api/categories'
        );
        $responseContent = $response->getContent();

        $responseDecoded = json_decode($responseContent);
        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    /**
     * @group func
     * @group funcCategory
     * @group crudCategory
     */
    public function testPostCategory(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_POST,
            '/api/categories',
            $this->randomPayload,
            [],
            true,
            $this->userLoginCredential
        );
        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent, true);

        self::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);

        /* Delete the Category previously created */
        $this->categoryManager->deleteOne($responseDecoded['id']);
    }

    /**
     * @group func
     * @group funcCategory
     * @group crudCategory
     */
    public function testGetOneCategory(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_GET,
            '/api/categories/'.$this->category->getId(),
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
     * @group funcCategory
     * @group crudCategory
     */
    public function testPatchCategory(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_PATCH,
            '/api/categories/'.$this->category->getId(),
            $this->randomPayload,
            [],
            true,
            $this->userLoginCredential
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent, true);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    /**
     * @group func
     * @group funcCategory
     * @group crudCategory
     */
    public function testPutCategory(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_PUT,
            '/api/categories/'.$this->category->getId(),
            $this->randomPayload,
            [],
            true,
            $this->userLoginCredential
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent, true);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    /**
     * @group func
     * @group funcCategory
     * @group crudCategory
     */
    public function testDeleteCategory(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_DELETE,
            '/api/categories/'.$this->category->getId(),
            '',
            [],
            true,
            $this->userLoginCredential
        );
        self::assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
