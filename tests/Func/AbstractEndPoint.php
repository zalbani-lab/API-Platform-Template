<?php

declare(strict_types=1);

namespace App\Tests\Func;

use App\DataFixtures\DefaultUserFixtures;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractEndPoint extends WebTestCase
{
    use SymfonyComponent;
    protected array $serverInformation = ['ACCEPT' => 'application/json', 'CONTENT_TYPE' => 'application/json'];
    protected string $tokenNotFound = 'JWT Token not found';
    protected string $accessDenied = 'Désoler, mais vous n\'avez pas les autorisations nécessaires pour effectuer cette action.';
    protected string $loginPayload = '{"username": "%s", "password": "%s"}';

    public function getResponseFromRequest(
        string $method,
        string $uri,
        string $payload = '',
        array $parameters = [],
        bool $withAuthentication = false,
        array $loginInformation = ['email' => DefaultUserFixtures::DEFAULT_USER['email'], 'password' => DefaultUserFixtures::DEFAULT_USER['password']]
    ): Response {
        $client = $this->createAuthenticationClient($withAuthentication, $loginInformation);

        $client->request(
            $method,
            $uri.'?log=false&email=false',
            $parameters,
            [],
            $this->serverInformation,
            $payload
        );

        return $client->getResponse();
    }

    protected function createAuthenticationClient(bool $withAuthentication, array $loginInformation): KernelBrowser
    {
        self::getKernel();
        if (!$withAuthentication) {
            return self::$kernelBrowser;
        }
        self::$kernelBrowser->request(
            Request::METHOD_POST,
            '/api/login',
            [],
            [],
            $this->serverInformation,
            sprintf($this->loginPayload, $loginInformation['email'], $loginInformation['password'])
        );

        $data = json_decode(self::$kernelBrowser->getResponse()->getContent(), true);
        self::$kernelBrowser->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return self::$kernelBrowser;
    }
}
