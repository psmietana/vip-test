<?php

namespace App\SMS;

interface SenderInterface
{
    public function send(string $message, string $to): void;
}
