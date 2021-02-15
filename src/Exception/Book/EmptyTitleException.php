<?php


namespace App\Exception\Book;


use Exception;

class EmptyTitleException extends Exception
{
    public function __construct()
    {
        parent::__construct('Book title cannot be empty');
    }
}