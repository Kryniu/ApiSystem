<?php


namespace App\Validator\Api;


use Symfony\Component\Validator\Constraints as Assert;

class UserValidator
{
    /**
     * @Assert\NotBlank(message="The first name cannot be empty")
     */
    private $firstName;

    private $lastName;

    /**
     * @Assert\NotBlank(message="The email cannot be empty")
     * @Assert\Email(message = "The email '{{ value }}' is not a valid email.")
     */
    private $email;

    private $slug;

    public function __construct($firstName, $lastName, $email, $slug)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->slug = $slug;
    }
}