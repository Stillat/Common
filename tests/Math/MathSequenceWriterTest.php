<?php

use Stillat\Common\Math\ExpressionEngines\NativeExpressionEngine;
use Stillat\Common\Math\FluentCalculator;
use Stillat\Common\Math\OperationSequenceWriter;

class MathSequenceWriterTest extends PHPUnit_Framework_TestCase
{

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

}