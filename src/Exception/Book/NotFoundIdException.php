<?php


namespace App\Exception\Book;


use Exception;

class NotFoundIdException extends Exception
{
    public function __construct(int $id)
    {
        parent::__construct("Not found book id: {$id}");
    }
}