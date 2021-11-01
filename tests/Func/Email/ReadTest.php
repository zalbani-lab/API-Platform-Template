<?php

declare(strict_types=1);

namespace App\Tests\Func\Email;

use App\Tests\Func\AbstractEndPoint;
use App\Tests\Func\Email\Utils\SetUpEmail;
use App\Tests\Func\Email\Utils\TearDownEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ReadTest extends AbstractEndPoint
{
    use SetUpEmail;
    use TearDownEmail;

    /**
     * @group func
     * @group funcEmail
     * @group readEmail
     */
    public function testGetEmails(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_GET,
            '/api/emails',
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
     * @group funcEmail
     * @group readEmail
     */
    public function testGetOneEmail(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_GET,
            '/api/emails/'.$this->email->getId(),
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
}
