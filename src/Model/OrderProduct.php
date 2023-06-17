<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class OrderProduct
{
    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotBlank]
    public string $phone;

    #[Assert\Email]
    public string $email;

    public ?string $location;

    #[Assert\NotBlank]
    public string $description;
}
