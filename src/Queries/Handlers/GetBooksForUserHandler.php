<?php

namespace App\Queries\Handlers;

use App\Entity\Book;
use App\Queries\GetBooksForUserQuery;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use InvalidArgumentException;

class GetBooksForUserHandler
{
    private $userRepository;
    private $bookRepository;

    public function __construct(UserRepository $userRepository, BookRepository $bookRepository)
    {
        $this->userRepository = $userRepository;
        $this->bookRepository= $bookRepository;
    }

    public function handle(GetBooksForUserQuery $query): array
    {
        if (null === $user = $this->userRepository->find($query->getId())) {
            throw new InvalidArgumentException('User not found');
        }

        $books = $this->bookRepository->findAllForUser($user);

        return array_map(function (Book $book) {
            return [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'shortDescription' => $book->getShortDescription(),
            ];
        }, $books);
    }
}
