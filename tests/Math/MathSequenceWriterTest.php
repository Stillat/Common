<?php

use Stillat\Common\Math\ExpressionEngines\NativeExpressionEngine;
use Stillat\Common\Math\FluentCalculator;
use Stillat\Common\Math\OperationSequenceWriter;

class MathSequenceWriterTest extends PHPUnit_Framework_TestCase
{

    protected $singleParameterFunctions = [
        'abs',
        'acos',
        'asin',
        'atan',
        'cos',
        'cosh',
        'exp',
        'sin',
        'sinh',
        'sqrt',
        'tan',
        'tanh',
        'floor',
        'log10',
        'ceiling',
        'truncate'
    ];

    protected $twoParameterFunctions = [
        'atan2',
        'log',
        'pow',
        'mod'
    ];

    protected $threeParametersFunctions = [
        'round'
    ];

    protected $arrayParameterFunctions = [
        'max',
        'min'
    ];

    /**
     * @var FluentCalculator
     */
    protected $calc;

    /**
     * @var OperationSequenceWriter
     */
    protected $writer;

    public function setUp()
    {
        $this->calc = new FluentCalculator(new NativeExpressionEngine());
        $this->writer = new OperationSequenceWriter();
    }

    protected function getExpression()
    {
        return $this->writer->write($this->calc->getHistory());
    }

    public function testWriterHandlesBasicOperations()
    {
        $this->calc->set(10)->add(10);
        $this->assertEquals('10 + 10', $this->getExpression());
        $this->calc->subtract(5);
        $this->assertEquals('10 + 10 - 5', $this->getExpression());
        $this->calc->multiply(50);
        $this->assertEquals('10 + 10 - 5 * 50', $this->getExpression());
        $this->calc->divide(2);
        $this->assertEquals('10 + 10 - 5 * 50 / 2', $this->getExpression());
        $this->calc->reset()->set(10)->add(10)->subtract(5)->multiply(50)->divide(2);
        $this->assertEquals('10 + 10 - 5 * 50 / 2', $this->getExpression());
    }

    public function testWriterHandlesGroups()
    {
        $this->calc->set(10)->add()->group(function (FluentCalculator $calc) {
            $calc->set(10)->add(20);
        });
        $this->assertEquals('10 + (10 + 20)', $this->getExpression());
    }

    public function testWriterHandlesNestedGroups()
    {
        $this->calc->set(10)->add()->group(function (FluentCalculator $calc) {
            $calc->group(function (FluentCalculator $calc) {
                $calc->set(10)->add(20);
            });
        });

        $this->assertEquals('10 + ((10 + 20))', $this->getExpression());

        $this->calc->reset()->set(30)->subtract()->group(function (FluentCalculator $calc) {
            $calc->group(function (FluentCalculator $calc) {
                $calc->set(10)->add(20);
            });
        });

        $this->assertEquals('30 - ((10 + 20))', $this->getExpression());
    }

    public function testWriterHandlesDeeplyNestedGroups()
    {

        // Test 2 + (3 - 3 + (33 - 23 + (2 * (9 / 3))))

        $this->calc->set(2)->add()->group(function (FluentCalculator $calc) {
            $calc->add(3)->subtract(3)->add()->group(function (FluentCalculator $calc) {
                $calc->add(33)->subtract(23)->add()->group(function (FluentCalculator $calc) {
                    $calc->set(2)->multiply()->group(function (FluentCalculator $calc) {
                        $calc->set(9)->divide(3);
                    });
                });
            });
        });

        $this->assertEquals('2 + (3 - 3 + (33 - 23 + (2 * (9 / 3))))', $this->getExpression());
    }


    public function testWriterCanHandleSingleGroups()
    {
        $this->calc->add()->group(function (FluentCalculator $calc) {
            $calc->add(10);
        });
        $this->assertEquals('(10)', $this->getExpression());
    }

    public function testWriterCanHandleSingleParameterFunctions()
    {
        foreach ($this->singleParameterFunctions as $func) {
            $this->calc->reset()->add()->{$func}(-10);
            $this->assertEquals($func . '(-10)', $this->getExpression());
            $this->calc->reset()->add(10)->add()->{$func}(-5);
            $this->assertEquals('10 + ' . $func . '(-5)', $this->getExpression());
            $this->calc->reset()->add(10)->add(-5)->subtract()->group(function (FluentCalculator $calc) use ($func) {
                $calc->add()->{$func}(-30);
            });
            $this->assertEquals('10 + -5 - (' . $func . '(-30))', $this->getExpression());
        }
        $this->calc->reset()->add()->factorial(10);
        $this->assertEquals('10!', $this->getExpression());
        $this->calc->reset()->add(10)->add()->factorial(5)->add()->group(function(FluentCalculator $calc) {
            $calc->add()->factorial(20)->multiply(10)->subtract(2);
        });

        $this->assertEquals('10 + 5! + (20! * 10 - 2)', $this->getExpression());
    }

    public function testWriterCanHandleTwoParameterFunctions()
    {
        foreach ($this->twoParameterFunctions as $func) {
            $this->calc->reset()->add()->{$func}(1,1);
            $this->assertEquals($func.'(1,1)', $this->getExpression());
        }
    }

    public function testWriterCanHandleThreeParameterFunctions()
    {
        foreach ($this->threeParametersFunctions as $func) {
            $this->calc->reset()->add()->{$func}(1,1,1);
            $this->assertEquals($func.'(1,1,1)', $this->getExpression());
        }
    }

    public function testWriterCanHandleArrayParameterFunctions()
    {
        foreach ($this->arrayParameterFunctions as $func) {
            $this->calc->reset()->add()->{$func}([1,2,3]);
            $this->assertEquals($func.'([1,2,3])', $this->getExpression());
        }
    }

}