<?php

namespace AppBundle\Tests\Utils;

use AppBundle\Utils\Pesel;

/**
 * PeselTest
 */
class PeselTest extends \PHPUnit_Framework_TestCase
{
    public function testCheck()
    {
        $pesel = new Pesel();
        
        $this->assertTrue($pesel->check(98021504434));
        
        $this->assertTrue($pesel->check(983453504434));
        
    }
}
