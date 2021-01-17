<?php

namespace App\Commands\Handlers;

use App\Commands\DeleteUserCommand;
use App\Exceptions\UserHasBooksException;
use App\Repository\UserRepository;
use App\SMS\SmsSender;
use InvalidArgumentException;

class DeleteUserHandler
{
    private $userRepository;
    private $smsSender;

    public function __construct(UserRepository $userRepository, SmsSender $smsSender)
    {
        $this->userRepository = $userRepository;
        $this->smsSender = $smsSender;
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

        $userPhoneNumber = $user->getPhoneNumber();
        $this->userRepository->remove($user);

        null !== $userPhoneNumber && $this->smsSender->send('User deleted', $userPhoneNumber);
    }
}
