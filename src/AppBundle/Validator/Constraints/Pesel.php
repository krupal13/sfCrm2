<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class Pesel
 */
class Pesel extends Constraint
{
    public $message = 'pesel';
    
    public function validatedBy()
    {
        return 'validator_pesel';
    }
}