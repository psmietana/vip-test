<?php

namespace App\Commands\Handlers;

use App\Commands\EditBookCommand;
use App\Repository\BookRepository;
use InvalidArgumentException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EditBookHandler
{
    private $bookRepository;
    private $validator;

    public function __construct(BookRepository $bookRepository, ValidatorInterface $validator)
    {
        $this->bookRepository = $bookRepository;
        $this->validator = $validator;
    }

    public function handle(EditBookCommand $command): void
    {
        if (null === $book = $this->bookRepository->find($command->getId())) {
            throw new InvalidArgumentException('User not found');
        }
        $book->setTitle($command->getTitle());
        $book->setDescription($command->getDescription());
        $book->setShortDescription($command->getShortDescription());

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
