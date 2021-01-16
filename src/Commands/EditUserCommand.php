<?php
declare(strict_types=1);

namespace App\Commands;

class EditUserCommand
{
    private $id;
    private $firstName;
    private $lastName;
    private $email;

    public function __construct(int $id, ?string $firstName, ?string $lastName, string $email)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
