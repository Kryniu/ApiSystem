<?php


namespace App\Exception\User;


use Exception;

class NotFoundIdException extends Exception
{
    public function __construct(int $id)
    {
        parent::__construct("Not found user id: {$id}");
    }
}