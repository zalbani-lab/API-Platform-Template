<?php

declare(strict_types=1);

namespace App\Controller\Email;

use App\Entity\Email;
use App\Services\Emailing\EmailSenderInterface;
use App\Services\ResponseBuilderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class PostEmail
{
    private EmailSenderInterface $email;
    private ResponseBuilderInterface $responseBuilder;

    public function __construct(EmailSenderInterface $emailSender, ResponseBuilderInterface $responseBuilder)
    {
        $this->email = $emailSender;
        $this->responseBuilder = $responseBuilder;
    }

    public function __invoke(Email $data, EntityManagerInterface $manager): Response
    {
        $recipientsArray = $this->transformStringIntoArray($data->getRecipient());
        if ($data->getContent() != strip_tags($data->getContent())) {
            $this->email->sendRawEmail($recipientsArray, $data->getReplyAddress(), $data->getSubject(), $data->getContent(), 'Email send via POST request');
        } else {
            $this->email->sendNormalEmail($recipientsArray, $data->getReplyAddress(), $data->getSubject(), $data->getContent(), 'Email send via POST request');
        }

        return $this->responseBuilder->getResponse(201, 'Email(s) envoye avec succes');
    }

    private function transformStringIntoArray(string $string): array
    {
        return explode(';', $string);
    }
}
