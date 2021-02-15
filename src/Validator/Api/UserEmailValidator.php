<?php


namespace App\Validator\Api;


use Symfony\Component\Validator\Constraints as Assert;

class UserEmailValidator
{

    /**
     * @Assert\NotBlank(message="The email cannot be empty")
     * @Assert\Email(message = "The email '{{ value }}' is not a valid email")
     */
    private $email;

    public function __construct($email)
    {
        $this->email = $email;
    }
}