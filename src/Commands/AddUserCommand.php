<?php
declare(strict_types=1);

namespace App\Commands;

class AddUserCommand
{
    private $firstName;
    private $lastName;
    private $email;
    private $phoneNumber;

    public function __construct(?string $firstName, ?string $lastName, string $email, string $phoneNumber)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
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

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }
}
