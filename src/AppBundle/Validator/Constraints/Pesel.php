<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class Pesel
 */
class Pesel extends Constraint
{
    public $message = 'Nieprawidłowy PESEL';
    
    public function validatedBy()
    {
        return 'validator_pesel';
    }
}