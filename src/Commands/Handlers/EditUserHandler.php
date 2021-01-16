<?php

namespace App\Commands\Handlers;

use App\Commands\EditUserCommand;
use App\Repository\UserRepository;
use InvalidArgumentException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EditUserHandler
{
    private $userRepository;
    private $validator;

    public function __construct(UserRepository $userRepository, ValidatorInterface $validator)
    {
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function handle(EditUserCommand $command): void
    {
        if (null === $user = $this->userRepository->find($command->getId())) {
            throw new InvalidArgumentException('User not found');
        }

        $user->setFirstName($command->getFirstName());
        $user->setLastName($command->getLastName());
        $user->setEmail($command->getEmail());
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
