<?php
declare(strict_types=1);

namespace App\Commands;

class AssignBookToUserCommand
{
    private $bookId;
    private $userId;

    public function __construct(int $bookId, int $userId)
    {
        $this->bookId = $bookId;
        $this->userId = $userId;
    }

    public function getBookId(): int
    {
        return $this->bookId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}
