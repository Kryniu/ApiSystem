<?php


namespace App\Exception;


use Exception;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidatorException extends Exception
{
    private const ERROR_MESSAGE_SEPARATOR = ', ';

    public function __construct(ConstraintViolationListInterface $errors)
    {
        parent::__construct($this->getErrorMessage($errors));
    }

    private function getErrorMessage(ConstraintViolationListInterface $errors): string
    {
        $errorMessage = [];
        foreach ($errors as $error) {
            $errorMessage[] = $error->getMessage();
        }

        return implode(self::ERROR_MESSAGE_SEPARATOR, $errorMessage);
    }
}