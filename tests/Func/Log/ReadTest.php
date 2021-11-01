<?php

declare(strict_types=1);

namespace App\Tests\Func\Log;

use App\Tests\Func\AbstractEndPoint;
use App\Tests\Func\Log\Utils\SetUpLog;
use App\Tests\Func\Log\Utils\TearDownLog;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ReadTest extends AbstractEndPoint
{
    use SetUpLog;
    use TearDownLog;

    /**
     * @group func
     * @group funcLog
     * @group readLog
     */
    public function testGetLogs(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_GET,
            '/api/logs',
            '',
            [],
            true,
            $this->userAdminCredential
        );
        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    /**
     * @group func
     * @group funcLog
     * @group readLog
     */
    public function testGetOneLog(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_GET,
            '/api/logs/'.$this->log->getId(),
            '',
            [],
            true,
            $this->userAdminCredential
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }
}
