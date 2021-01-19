<?php

namespace App\Commands\Handlers;

use App\Commands\AssignBookToUserCommand;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use InvalidArgumentException;

class AssignBookToUserHandler
{
    private $userRepository;
    private $bookRepository;

    public function __construct(UserRepository $userRepository, BookRepository $bookRepository)
    {
        $this->userRepository = $userRepository;
        $this->bookRepository= $bookRepository;
    }

    public function handle(AssignBookToUserCommand $command): void
    {
        if (null === $user = $this->userRepository->find($command->getUserId())) {
            throw new InvalidArgumentException('User not found');
        }
        if (null === $book = $this->bookRepository->find($command->getBookId())) {
            throw new InvalidArgumentException('Book not found');
        }
        $book->addUser($user);
        $this->bookRepository->save($book);
    }
}
