<?php

namespace AppBundle\Utils;

/**
 * Class Pesel
 */
class Pesel
{
    private $pesel_weights = array(9, 7, 3, 1, 9, 7, 3, 1, 9, 7);
    
    public function check($value)
    {
        $valid = true;
        if (!empty($value)) {
            if (strlen($value) == 11 && ctype_digit($value)) {
                $sum = 0;
                for ($i = 0; $i < 10; $i++)
                    $sum += $value[$i] * $this->pesel_weights[$i];
                $valid = ($sum % 10) == $value[10];
                if ($valid) {
                    $year = intval(substr($value, 0, 2));
                    $month = intval(substr($value, 2, 2));
                    $day = intval(substr($value, 4, 2));
                    if (($month >= 1) && ($month <= 12)) // 1900 - 1999
                    {
                        $year += 1900;
                    } else if (($month >= 81) && ($month <= 92)) // 1800 - 1899
                    {
                        $year += 1800;
                        $month = $month - 80;
                    } else if (($month >= 21) && ($month <= 32)) {
                        $year += 2000;
                        $month = $month - 20;
                    }
                    $now = new \DateTime();

                    if (!checkdate($month, $day, $year)) {
                        $valid = false;
                    } else {
                        $birth_day = new \DateTime($year . '-' . $month . '-' . $day);
                        $valid = checkdate($month, $day, $year) && ($now > $birth_day);
                    }

                }
            } else {
                $valid = false;
            }
        }
        
        return $valid;
    }
}