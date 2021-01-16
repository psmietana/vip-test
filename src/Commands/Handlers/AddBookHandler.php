<?php

namespace App\Commands\Handlers;

use App\Commands\AddBookCommand;
use App\Entity\Book;
use App\Repository\BookRepository;
use InvalidArgumentException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddBookHandler
{
    private $bookRepository;
    private $validator;

    public function __construct(BookRepository $bookRepository, ValidatorInterface $validator)
    {
        $this->bookRepository = $bookRepository;
        $this->validator = $validator;
    }

    public function handle(AddBookCommand $command): void
    {
        $book = new Book(
            $command->getTitle(),
            $command->getDescription(),
            $command->getShortDescription()
        );
        $errors = $this->validator->validate($book);
        if (count($errors)) {
            $errorString = '';
            foreach ($errors as $error) {
                $errorString .= $error->getMessage();
            }
            throw new InvalidArgumentException($errorString);
        }

        $this->bookRepository->save($book);
    }
}
