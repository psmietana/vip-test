<?php
declare(strict_types=1);

namespace App\Commands;

class EditBookCommand
{
    private $id;
    private $title;
    private $description;
    private $shortDescription;

    public function __construct(int $id, string $title, string $description, string $shortDescription)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->shortDescription = $shortDescription;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getShortDescription(): string
    {
        return $this->shortDescription;
    }
}
