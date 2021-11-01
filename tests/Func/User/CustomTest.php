<?php

declare(strict_types=1);

namespace App\Tests\Func\User;

use App\Tests\Func\AbstractEndPoint;
use App\Tests\Func\User\Utils\SetUpUser;
use App\Tests\Func\User\Utils\TearDownUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomTest extends AbstractEndPoint
{
    use SetUpUser;
    use TearDownUser;

    /**
     * @group func
     * @group funcUser
     * @group customUser
     */
    public function testGetCurrentUser(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_GET,
            '/api/users/current',
            '',
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
}
