<?php

namespace App\Commands\Handlers;

use App\Commands\DeleteUserCommand;
use App\Exceptions\UserHasBooksException;
use App\Repository\UserRepository;
use InvalidArgumentException;

class DeleteUserHandler
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(DeleteUserCommand $command): void
    {
        if (null === $user = $this->userRepository->find($command->getId())) {
            throw new InvalidArgumentException('User not found');
        }

        $userBooksCount = $this->userRepository->getBooksCountForUserId($command->getId());
        if ($userBooksCount > 0) {
            throw new UserHasBooksException('User has books. Cannot delete.');
        }

        $this->userRepository->remove($user);


    }
}
