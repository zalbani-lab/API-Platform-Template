<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\Mapping\MappingException;
use phpDocumentor\Reflection\DocBlock\Tags\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\Security\Core\Security;

class LogCreation implements LogCreationInterface
{
    protected array $targetElementWithSensitiveData = [
        'users',
    ];

    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public function createLog(TerminateEvent $event): void
    {
        //@todo: Need a refactoring
        // Enregister l'adresse IP de l'emetteur ?
        // $clientIp = $event->getRequest()->getClientIp();
        $author = null;

        $method = $event->getRequest()->getMethod();
        $requestContent = $event->getRequest()->getContent();
        $responseContent = $event->getResponse()->getContent();
        $requestUri = $event->getRequest()->getPathInfo();
        $statusCode = $event->getResponse()->getStatusCode();

        $targetElement = $this->getTargetElementWithUri($requestUri);
        $targetId = $this->getTargetId($requestUri, $method, $responseContent);

        if ($this->security->getUser()) {
            $author = $this->security->getUser()->getId();
            /* Cas particulier : lors ce aue l'on se supprime soit meme.
            le user dans security existe toujours mais celui-ci ne possede plus d'id car
            il n'est plus representer en base de donnee. Le bout de code ci-dessous palie au probleme */
            if (null === $author) {
                $author = $targetId;
            }
        }

        $request = [];
        if (in_array($targetElement, $this->targetElementWithSensitiveData, true)) {
            array_push($request, 'This request have to be private. She contain sensitive data (password, email ...) ');
        } else {
            $request['uri'] = $requestUri;
            $request['body'] = json_decode($requestContent);
        }

        $response = [];
        array_push($response, json_decode($responseContent));

        $level = $this->getLevel($statusCode);

        $tempLog = new Log();
        $tempLog->setMethod($method)
                ->setRequest($request)
                ->setResponse($response)
                ->setTargetId($targetId)
                ->setTargetElement($targetElement)
                ->setAuthor($author)
                ->setLevel($level);
        try {
            $this->entityManager->persist($tempLog);
            $this->entityManager->flush();
            $this->entityManager->clear();
        } catch (ORMException | MappingException $e) {
            // @todo: Write something here
            // var_dump($e);
        }
    }

    private function getTargetElementWithUri(string $uri): ?string
    {
        if (strrpos($uri, '/')) {
            $explodeUri = explode('/', $uri);
            return $explodeUri[2];
        }
    }

    private function getTargetId(string $uri, String $method, String $responseContent): ?int
    {
        if ($method === Request::METHOD_POST) {
            $decodeResponseContent = json_decode($responseContent, true);
            if (array_key_exists('id', $decodeResponseContent)) {
                return  $decodeResponseContent['id'];
            }
            return null;
        }

        $int = (int) filter_var($uri, FILTER_SANITIZE_NUMBER_INT);
        if ($int) {
            return $int;
        }

        return null;
    }

    private function getLevel(int $statusCode): int
    {
        $stringStatusCode = strval($statusCode);
        switch (true) {
            case preg_match('/1.[0-9]/', $stringStatusCode):
                return 1;
            case preg_match('/2.[0-9]/', $stringStatusCode):
                return 2;
            case preg_match('/3.[0-9]/', $stringStatusCode):
                return 3;
            case preg_match('/4.[0-9]/', $stringStatusCode):
                return 4;
            case preg_match('/5.[0-9]/', $stringStatusCode):
                return 5;
            default:
                return 0;
        }
    }
}
