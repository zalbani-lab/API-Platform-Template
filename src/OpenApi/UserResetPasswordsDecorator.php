<?php

declare(strict_types=1);

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\Model;
use ApiPlatform\Core\OpenApi\OpenApi;

class UserResetPasswordsDecorator implements OpenApiFactoryInterface
{
    private OpenApiFactoryInterface $decorated;

    public function __construct(
        OpenApiFactoryInterface $decorated
    ) {
        $this->decorated = $decorated;
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);
        $pathItem = $openApi->getPaths()->getPath('/api/users/forgottenPasswords');
        $operation = $pathItem->getGet();

        $openApi->getPaths()->addPath('/api/users/forgottenPasswords', $pathItem->withGet(
            $operation->withParameters([new Model\Parameter('email', 'query', 'user\'s address')])
        ));

        return $openApi;
    }
}
