<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User;
use App\Services\Emailing\EmailSenderInterface;
use App\Services\ResponseBuilderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserForgottenPasswords
{
    private EmailSenderInterface $mailer;
    private EntityManagerInterface $manager;
    private UserPasswordEncoderInterface $passwordEncoder;
    private ResponseBuilderInterface $responseBuilder;

    private \Faker\Generator $faker;

    public function __construct(EmailSenderInterface $mailer, EntityManagerInterface $manager, UserPasswordEncoderInterface $passwordEncoder, ResponseBuilderInterface $responseBuilder)
    {
        $this->mailer = $mailer;
        $this->manager = $manager;
        $this->passwordEncoder = $passwordEncoder;
        $this->responseBuilder = $responseBuilder;
        $this->faker = Factory::create();
    }

    public function __invoke(RequestStack $requestStack): Response
    {
        $requestQuery = $requestStack->getCurrentRequest()->getQueryString();

        if (null === $requestQuery) {
            throw new Exception('ERROR_CODE % 400 % Query argument not valid try : email');
        } else {
            $result = explode('=', $requestQuery);
            $argument = $result[0];
            $value = urldecode($result[1]);

            $user = $this->manager->getRepository(User::class)
                ->findOneByEmail($value);
            if (null !== $user && $user instanceof User) {
                switch ($argument) {
                    case 'email':
                        $this->updateUser($user);
                        break;
                    default:
                        throw new Exception('ERROR_CODE % 400 % Query argument not valid try : email instead of : '.$argument);
                }
            } else {
                throw new Exception('ERROR_CODE % 400 % No user found with the email address : '.$value);
            }

            $message = 'A new password has been sent at the address : '.$value;

            return $this->responseBuilder->getResponse(Response::HTTP_OK, $message);
        }
    }

    private function generateNewPassword(): string
    {
        return $this->removeSpecialCharacter($this->faker->password);
    }

    private function removeSpecialCharacter(string $passwordGeneratedByFaker): string
    {
        return preg_replace('/[^A-Za-z0-9\-]/', '', $passwordGeneratedByFaker); // Removes special chars.
    }

    private function hashPassword(User $user, string $password): string
    {
        return $this->passwordEncoder->encodePassword($user, $password);
    }

    private function updateUser(User $user): User
    {
        $newPassword = $this->generateNewPassword();
        $encodedPassWord = $this->hashPassword($user, $newPassword);
        $user->setPassword($encodedPassWord);
        $this->saveUser($user);
        $this->sendEmail($user->getEmail(), $newPassword);

        return $user;
    }

    private function sendEmail(string $userEmailAddress, string $newPassword): void
    {
        $subject = 'Coucou c\'est le sujet';
        $templateName = 'resetPassword';
        $templateArgument = [
            'newPassword' => $newPassword,
        ];
        $emailLogContext = 'User action';

        $this->mailer->sendOneTemplateEmail($userEmailAddress, $subject, $templateName, $templateArgument, $emailLogContext);
    }

    private function saveUser(User $user)
    {
        $this->manager->persist($user);
        $this->manager->flush();
        $this->manager->clear();
    }
}
