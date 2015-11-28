<?php

use Stillat\Common\Math\FluentCalculator;
use Stillat\Common\Math\ExpressionEngines\NativeExpressionEngine;

class MathFluentCalculatorTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var FluentCalculator
     */
    protected $calculator;

    protected $maxMinSet = [3, 23, 35, 1, 43.3];

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

        $this->calculator->with('sales')->add([10, 10, 10]);
        $this->assertEquals(30, $this->calculator->get('sales'));
    }

    public function testFluentCalculatorCanSubtract()
    {
        $this->calculator->set(10)->subtract(10);
        $this->assertEquals(0, $this->calculator->get());

        $this->calculator->add(20)->subtract([10, 5, 5]);
        $this->assertEquals(0, $this->calculator->get());
    }

    public function testFluentCalculatorCanMultiply()
    {
        $this->calculator->set(1)->multiply(10);
        $this->assertEquals(10, $this->calculator->get());

        $this->calculator->set(0)->multiply(10);
        $this->assertEquals(0, $this->calculator->get());

        $this->calculator->set(1)->multiply([5, 5]);
        $this->assertEquals(25, $this->calculator->get());
    }

    public function testFluentCalculatorCanDivide()
    {
        $this->calculator->set(25)->divide(5);
        $this->assertEquals(5, $this->calculator->get());

        $this->calculator->set(8)->divide([2, 2]);
        $this->assertEquals(2, $this->calculator->get());
    }

    /**
     * @expectedException \Stillat\Common\Exceptions\Arithmetic\DivideByZeroException
     */
    public function testFluentCalculatorThrowsDivideByZeroException()
    {
        $this->calculator->divide(0);
    }

    public function testFluentCalculatorGrouping()
    {
        $this->calculator->add(2)->subtract(4)->add()->group(function (FluentCalculator $calc) {
            $calc->add(3)->multiply(3);
        });
        $this->assertEquals(7, $this->calculator->get());

        /**
         * Test 2 + (3 - 3 + (33 - 23 + (2 * (9 / 3))))
         */
        $this->calculator->set(2)->add()->group(function (FluentCalculator $calc) {
            $calc->add(3)->subtract(3)->add()->group(function (FluentCalculator $calc) {
                $calc->add(33)->subtract(23)->add()->group(function (FluentCalculator $calc) {
                    $calc->set(2)->multiply()->group(function (FluentCalculator $calc) {
                        $calc->set(9)->divide(3);
                    });
                });
            });
        });
        $this->assertEquals(18, $this->calculator->get());
    }

    public function testFluentAbsFunctionCalls()
    {
        $this->calculator->set(10)->add()->abs(-20)->abs(-20)->abs(-200);
        $this->assertEquals(250, $this->calculator->get());
    }

    public function testFluentAcosFunctionCalls()
    {
        $this->calculator->withPrecision(4)->add()->acos('0.15');
        $this->assertEquals('1.4202', $this->calculator->get());

        $this->calculator->reset()->withPrecision(4)->add()->acos('0.15')->subtract()->acos('0.15');
        $this->assertEquals('0', $this->calculator->get());
    }

    public function testFluentAsinFunctionCalls()
    {
        $this->calculator->withPrecision(4)->add()->asin('0.15');
        $this->assertEquals('0.1506', $this->calculator->get());

        $this->calculator->reset()->withPrecision(4)->add()->asin(0.15)->subtract()->asin(0.15);
        $this->assertEquals(0, $this->calculator->get());
    }

    public function testFluentAtanFunctionCalls()
    {
        $this->calculator->withPrecision(4)->add()->atan(0.15);
        $this->assertEquals('0.1489', $this->calculator->get());

        $this->calculator->reset()->withPrecision(4)->add()->atan(0.15)->subtract()->atan(0.15);
        $this->assertEquals(0, $this->calculator->get());
    }

    public function testFluentAtan2FunctionCalls()
    {
        $this->calculator->withPrecision(4)->add()->atan2(0.15, 0.45);
        $this->assertEquals(0.3218, $this->calculator->get());
    }

    public function testFluentCosFunctionCalls()
    {
        $this->calculator->withPrecision(4)->add()->cos(0.23);
        $this->assertEquals(0.9737, $this->calculator->get());
    }

    public function testFluentCoshFunctionCalls()
    {
        $this->calculator->withPrecision(4)->add()->cosh(0.23);
        $this->assertEquals(1.0266, $this->calculator->get());
    }

    public function testFluentExpFunctionCalls()
    {
        $this->calculator->withPrecision(4)->add()->exp(0);
        $this->assertEquals('1.0000', $this->calculator->get());
        $this->calculator->reset()->add()->exp('2');
        $this->assertEquals('7.3891', $this->calculator->get());

    }

    public function testFluentLogFunctionCalls()
    {
        $this->calculator->withPrecision(4)->add()->log(2);
        $this->assertEquals(0.6931, $this->calculator->get());
    }

    public function testFluentPowFunctionCalls()
    {
        $this->calculator->withPrecision(4)->add()->pow(2, 32);
        $this->assertEquals('4294967296.0000', $this->calculator->get());
    }

    public function testFluentSinFunctionCalls()
    {
        $this->calculator->withPrecision(4)->add()->sin(7);
        $this->assertEquals(0.6570, $this->calculator->get());
    }

    public function testFluentSinhFunctionCalls()
    {
        $this->calculator->withPrecision(4)->add()->sinh(7);
        $this->assertEquals(548.3161, $this->calculator->get());
    }

    public function testFluentSqrtFunctionCalls()
    {
        $this->calculator->withPrecision(4)->add()->sqrt(144);
        $this->assertEquals(12.0000, $this->calculator->get());
    }

    public function testFluentTanFunctionCalls()
    {
        $this->calculator->withPrecision(4)->add()->tan(5);
        $this->assertEquals(-3.3805, $this->calculator->get());
    }

    public function testFluentTanhFunctionCalls()
    {
        $this->calculator->withPrecision(4)->add()->tanh(5);
        $this->assertEquals(0.9999, $this->calculator->get());
    }

    public function testFluentModFunctionCalls()
    {
        $this->calculator->add()->mod(23, 233);
        $this->assertEquals(23, $this->calculator->get());
        $this->calculator->reset()->add()->mod(23, 23);
        $this->assertEquals(0, (string)$this->calculator);
    }

    public function testFluentFactorialFunctionCalls()
    {
        $this->calculator->add()->factorial(5);
        $this->assertEquals(120, $this->calculator->get());
    }

    public function testFluentFloorFunctionCalls()
    {
        $this->calculator->add()->floor(0);
        $this->assertEquals(0, $this->calculator->get());
    }

    public function testFluentTruncateFunctionCalls()
    {
        $this->calculator->add()->truncate('1.223');
        $this->assertEquals(1, $this->calculator->get());
    }

    public function testFluentCeilingFunctionCalls()
    {
        $this->calculator->add()->ceiling(0.2);
        $this->assertEquals(1,$this->calculator->get());
    }

    public function testFluentLog10FunctionCalls()
    {
        $this->calculator->add()->log10(10);
        $this->assertEquals(1, $this->calculator->get());
    }

    public function testFluentMaxFunctionCalls()
    {
        $this->calculator->withPrecision(4)->add()->max($this->maxMinSet);
        $this->assertEquals('43.3000', $this->calculator->get());
    }

    public function testFluentMinFunctionCalls()
    {
        $this->calculator->withPrecision(4)->add()->min($this->maxMinSet);
        $this->assertEquals('1.0000', $this->calculator->get());
    }

    public function testFluentRoundFunctionCalls()
    {
        $this->calculator->withPrecision(4)->add()->round('10.4');
        $this->assertEquals('10.0000', $this->calculator->get());
        $this->calculator->reset()->add()->round('10.4', 1);
        $this->assertEquals('10.4000', $this->calculator->get());
    }

    public function testFunctionsCanAcceptClosures()
    {
        $this->calculator->add()->abs(function() {
           return -10 * 1000;
        });

        $this->assertEquals(10000, $this->calculator->get());
    }

    public function testFunctionsCanAcceptClosuresWithFluentInstance()
    {
        $this->calculator->add()->abs(function (FluentCalculator $calc) {
           return $calc->set(10)->add(30)->multiply(-1);
        });

        $this->assertEquals(40, $this->calculator->get());
    }

}