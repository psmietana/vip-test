<?php

namespace App\Commands\Handlers;

use App\Commands\AddUserCommand;
use App\Entity\User;
use App\Repository\UserRepository;
use InvalidArgumentException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddUserHandler
{
    private $userRepository;
    private $validator;

    public function __construct(UserRepository $userRepository, ValidatorInterface $validator)
    {
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function handle(AddUserCommand $command): void
    {
        $user = new User(
            $command->getFirstName(),
            $command->getLastName(),
            $command->getEmail()
        );
        $errors = $this->validator->validate($user);
        if (count($errors)) {
            $errorString = '';
            foreach ($errors as $error) {
                $errorString .= $error->getMessage();
            }
            throw new InvalidArgumentException($errorString);
        }

        $this->userRepository->save($user);
    }
}
