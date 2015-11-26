<?php

use Stillat\Common\Math\FluentCalculator;
use Stillat\Common\Math\ExpressionEngines\NativeExpressionEngine;

class MathFluentCalculatorTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var FluentCalculator
     */
    protected $calculator;

    public function setUp()
    {
        $this->calculator = new FluentCalculator(new NativeExpressionEngine());
    }

    public function testFluentCalculatorCanKeepDifferentTotals()
    {
        $this->calculator->add(10);
        $this->calculator->with('sales')->add(30);

        $this->assertEquals(10, $this->calculator->get('default'));
        $this->assertEquals(30, $this->calculator->get('sales'));

        $this->calculator->subtract(5);
        $this->assertEquals(25, $this->calculator->get('sales'));
        $this->assertEquals(25, $this->calculator->get());
    }

    public function testYouCanSetInitialValue()
    {
        $this->calculator->set(10);
        $this->assertEquals(10, $this->calculator->get());
    }

    public function testFluentCalculatorCanAdd()
    {
        $this->calculator->add(10)->add(10);
        $this->assertEquals(20, $this->calculator->get());

        $this->calculator->with('sales')->add([10,10, 10]);
        $this->assertEquals(30, $this->calculator->get('sales'));
    }

    public function testFluentCalculatorCanSubtract()
    {
        $this->calculator->set(10)->subtract(10);
        $this->assertEquals(0, $this->calculator->get());

        $this->calculator->add(20)->subtract([10,5,5]);
        $this->assertEquals(0, $this->calculator->get());
    }

    public function testFluentCalculatorCanMultiply()
    {
        $this->calculator->set(1)->multiply(10);
        $this->assertEquals(10, $this->calculator->get());

        $this->calculator->set(0)->multiply(10);
        $this->assertEquals(0, $this->calculator->get());

        $this->calculator->set(1)->multiply([5,5]);
        $this->assertEquals(25, $this->calculator->get());
    }

    public function testFluentCalculatorCanDivide()
    {
        $this->calculator->set(25)->divide(5);
        $this->assertEquals(5, $this->calculator->get());

        $this->calculator->set(8)->divide([2,2]);
        $this->assertEquals(2, $this->calculator->get());
    }

    /**
     * @expectedException \Stillat\Common\Exceptions\DivideByZeroException
     */
    public function testFluentCalculatorThrowsDivideByZeroException()
    {
        $this->calculator->divide(0);
    }

    public function testFluentCalculatorGrouping()
    {
        $this->calculator->add(2)->subtract(4)->add()->group(function(FluentCalculator $calc) {
            $calc->add(3)->multiply(3);
        });
        $this->assertEquals(7, $this->calculator->get());

        /**
         * Test 2 + (3 - 3 + (33 - 23 + 2 * (9 /3 ) ) )
         */
        $this->calculator->set(2)->add()->group(function(FluentCalculator $calc) {
           $calc->add(3)->subtract(3)->add()->group(function(FluentCalculator $calc) {
               $calc->add(33)->subtract(23)->add()->group(function(FluentCalculator $calc) {
                   $calc->set(2)->multiply()->group(function(FluentCalculator $calc) {
                       $calc->set(9)->divide(3);
                 });
              });
           });
        });
        $this->assertEquals(18, $this->calculator->get());
    }

}