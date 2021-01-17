<?php
declare(strict_types=1);

namespace App\Queries;

class GetBooksForUserQuery
{
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
