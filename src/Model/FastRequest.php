<?php

namespace App\Model;
use Symfony\Component\Validator\Constraints as Assert;

class FastRequest
{
    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotBlank]
    public string $phone;
}
