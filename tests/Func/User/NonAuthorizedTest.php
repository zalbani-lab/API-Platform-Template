<?php

declare(strict_types=1);

namespace App\Tests\Func\User;

use App\Tests\Func\AbstractEndPoint;
use App\Tests\Func\User\Utils\SetUpUser;
use App\Tests\Func\User\Utils\TearDownUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NonAuthorizedTest extends AbstractEndPoint
{
    use SetUpUser;
    use TearDownUser;

    /**
     * @group func
     * @group funcUser
     * @group authorizationUser
     */
    public function testPutCurrentUserNotConnected(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_PUT,
            '/api/users/'.$this->user->getId(),
            $this->randomPayload,
        );
        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);
        self::assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    /**
     * @group func
     * @group funcUser
     * @group authorizationUser
     */
    public function testPatchCurrentUserNotConnected(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_PATCH,
            '/api/users/'.$this->user->getId(),
            $this->randomPayload
        );
        $responseContent = $response->getContent();

        $responseDecoded = json_decode($responseContent);
        self::assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    /**
     * @group func
     * @group funcUser
     * @group authorizationUser
     */
    public function testDeleteCurrentUserNotConnected(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_DELETE,
            '/api/users/'.$this->user->getId()
        );
        $responseContent = $response->getContent();

        $responseDecoded = json_decode($responseContent);
        self::assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    /**
     * @group func
     * @group funcUser
     * @group authorizationUser
     */
    public function testDeleteARandomUser(): void
    {
        /* Creation of a random user */
        $randomUser = $this->userManager->createOne();

        /* Trying to delete this random user */
        $response = $this->getResponseFromRequest(
            Request::METHOD_DELETE,
            '/api/users/'.$randomUser->getId(),
            '',
            [],
            true,
            $this->userLoginCredential
        );
        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent, true);

        self::assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
        self::assertEquals($this->accessDenied, $responseDecoded['message']);

        /* Clearing the DB, deleting the random user */
        $this->userManager->deleteOne($randomUser->getId());
    }
}
