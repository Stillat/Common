<?php

use Stillat\Common\Exceptions\Argument\ArgumentException;

class ExceptionTest extends PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \Stillat\Common\Contracts\Exceptions\ArgumentException
     */
    public function testYouCanCatchInterfaces()
    {
        throw new ArgumentException;
    }

}