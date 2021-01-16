<?php

namespace App\Commands\Handlers;

use App\Commands\DeleteBookCommand;
use App\Exceptions\BookUsedByUsersException;
use App\Repository\BookRepository;
use InvalidArgumentException;

class DeleteBookHandler
{
    private $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }


    public function handle(DeleteBookCommand $command): void
    {
        if (null === $book = $this->bookRepository->find($command->getId())) {
            throw new InvalidArgumentException('Book not found');
        }

        $userBooksCount = $this->bookRepository->getUsersCountForBookId($command->getId());
        if ($userBooksCount > 0) {
            throw new BookUsedByUsersException('Book is assigned to users. Cannot delete.');
        }

        $this->bookRepository->remove($book);
    }
}
