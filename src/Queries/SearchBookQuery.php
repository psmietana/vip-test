<?php
declare(strict_types=1);

namespace App\Queries;

class SearchBookQuery
{
    private $searchQuery;
    private $inDescritpion;

    public function __construct(string $searchQuery, ?bool $inDescritpion = false)
    {
        $this->searchQuery = $searchQuery;
        $this->inDescritpion = $inDescritpion;
    }

    public function getSearchQuery(): string
    {
        return $this->searchQuery;
    }

    public function getInDescription(): bool
    {
        return $this->inDescritpion;
    }
}
