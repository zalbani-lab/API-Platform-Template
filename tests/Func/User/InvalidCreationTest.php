<?php

declare(strict_types=1);

namespace App\Tests\Func\User;

use App\Tests\Func\AbstractEndPoint;
use App\Tests\Func\User\Utils\SetUpUser;
use App\Tests\Func\User\Utils\TearDownUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class InvalidCreationTest extends AbstractEndPoint
{
    use SetUpUser;
    use TearDownUser;

    /**
     * @group func
     * @group funcUser
     * @group invalidCreationUser
     */
    public function testPostUserWithSameEmail(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_POST,
            '/api/users',
            $this->userManager->getCustomPayload($this->user->getEmail(), $this->userManager->getRandomPassword())
        );
        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent, true);

        self::assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }
}
