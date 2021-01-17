<?php

namespace App\Queries\Handlers;

use App\Entity\Book;
use App\Queries\SearchBookQuery;
use App\Repository\BookRepository;

class SearchBookHandler
{
    private $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository= $bookRepository;
    }

    public function handle(SearchBookQuery $query): array
    {
        $searchQueryElements = explode(' ', $query->getSearchQuery());

        $q = $this->bookRepository->createQueryBuilder('b');
        foreach ($searchQueryElements as $key => $searchQueryElement) {
            $whereExpr = '(b.title LIKE :searchElem' . $key;
            $whereExpr .= true === $query->getInDescription() ? (' OR b.description LIKE :searchElem' . $key) : ')';

            $q->andWhere($whereExpr);
            $q->setParameter('searchElem' . $key, '%' . $searchQueryElement . '%');
        }

        return array_map(function (Book $book) {
            return [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'shortDescription' => $book->getShortDescription(),
            ];
        }, $q->getQuery()->getResult());
    }
}
