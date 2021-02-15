<?php


namespace App\Exception\User;


use Exception;

class EmailAlreadyExistException extends Exception
{
    public function __construct(string $email)
    {
        parent::__construct("User with this email {$email} already exist");
    }
}