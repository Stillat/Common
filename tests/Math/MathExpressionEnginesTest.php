<?php

use Stillat\Common\Math\ExpressionEngines\BinaryCalculatorExpressionEngine;
use Stillat\Common\Math\ExpressionEngines\NativeExpressionEngine;
use Stillat\Common\Math\Calculator;

class MathExpressionEnginesTest extends PHPUnit_Framework_TestCase
{

    protected $testPrecision = 4;
    protected $maxMinSet = [3, 23, 35, 1, 43.3];
    protected $manager = null;

    public function testBinaryCalculationEngine()
    {
        $this->manager = new Calculator(new BinaryCalculatorExpressionEngine());
        $this->manager->setPrecision($this->testPrecision);
        $this->runCalcTests();
    }

    public function testNativeEngine()
    {
        $this->manager = new Calculator(new NativeExpressionEngine());
        $this->manager->setPrecision($this->testPrecision);
        $this->runCalcTests();
    }

    /**
     * @expectedException \Stillat\Common\Exceptions\Arithmetic\DivideByZeroException
     */
    public function testBinaryCalculationEngineThrowsDivideByZeroException()
    {
        $manager = new Calculator(new BinaryCalculatorExpressionEngine());
        $manager->divide('5', '0');
    }

    /**
     * @expectedException \Stillat\Common\Exceptions\Arithmetic\DivideByZeroException
     */
    public function testNativeDriverThrowsDivideByZeroException()
    {
        $manager = new Calculator(new NativeExpressionEngine());
        $manager->divide('5', '0');
    }

    private function runCalcTests()
    {
        $this->assertEquals('2.0000', $this->manager->abs('-2'));
        $this->assertEquals('23.0000', $this->manager->abs('-23'));
        $this->assertEquals('23.0000', $this->manager->abs('23'));
        $this->assertEquals('1.4202', $this->manager->acos('0.15'));
        $this->assertEquals('1.4172', $this->manager->acos('0.153'));
        $this->assertEquals('0.1506', $this->manager->asin('0.15'));
        $this->assertEquals('0.1536', $this->manager->asin('0.153'));
        $this->assertEquals('0.1489', $this->manager->atan('0.15'));
        $this->assertEquals('0.1518', $this->manager->atan('0.153'));
        $this->assertEquals('0.3218', $this->manager->atan2('0.15', '0.45'));
        $this->assertEquals('0.7777', $this->manager->atan2('0.15213', '0.1545'));
        $this->assertEquals('1.0000', $this->manager->ceiling('0.2215'));
        $this->assertEquals('33.0000', $this->manager->ceiling('32.02'));

        $this->assertEquals('0.9737', $this->manager->cos('0.23'));
        $this->assertEquals('1.0000', $this->manager->cos('0'));
        $this->assertEquals('1.0266', $this->manager->cosh('0.23'));
        $this->assertEquals('1.0000', $this->manager->cosh('0'));
        $this->assertEquals('1.0000', $this->manager->exp('0'));
        $this->assertEquals('7.3891', $this->manager->exp('2'));
        $this->assertEquals('0.0000', $this->manager->floor('0'));
        $this->assertEquals('2.0000', $this->manager->floor('2'));
        $this->assertEquals('0.6931', $this->manager->log('2'));
        $this->assertEquals('3.0910', $this->manager->log('22'));
        $this->assertEquals('1.0000', $this->manager->log('2', '2'));
        $this->assertEquals('7.8600', $this->manager->log('232.32', '2'));
        $this->assertEquals('1.0000', $this->manager->log('10', '10'));
        $this->assertEquals('1.0000', $this->manager->log10('10'));
        $this->assertEquals('2.0000', $this->manager->log10('100'));
        $this->assertEquals($this->manager->log('10', '10'), $this->manager->log10('10'));
        $this->assertEquals('43.3000', $this->manager->max($this->maxMinSet));
        $this->assertEquals('1.0000', $this->manager->min($this->maxMinSet));
        $this->assertEquals('144.0000', $this->manager->pow('12', '2'));
        $this->assertEquals('4294967296.0000', $this->manager->pow('2', '32'));
        $this->assertEquals('10.0000', $this->manager->round('10.4', 0));
        $this->assertEquals('10.4000', $this->manager->round('10.4', 1));
        $this->assertEquals('-1', $this->manager->sign('-2'));
        $this->assertEquals('1', $this->manager->sign('2'));
        $this->assertEquals('0', $this->manager->sign('0'));
        $this->assertEquals('0.6570', $this->manager->sin('7'));
        $this->assertEquals('548.3161', $this->manager->sinh('7'));
        $this->assertEquals('12', $this->manager->sqrt('144'));
        $this->assertEquals('-3.3805', $this->manager->tan('5'));
        $this->assertEquals('0.9999', $this->manager->tanh('5'));
        $this->assertEquals('1', $this->manager->truncate('1.2346523'));
        $this->assertEquals('2', $this->manager->truncate('2.2346523'));

        $this->assertEquals('627628341587', $this->manager->add('392392359235', '235235982352'));
        $this->assertEquals('-235235982352', $this->manager->subtract('392392359235', '627628341587'));
        $hundred = $this->manager->multiply('10', '10');
        $thousand = $this->manager->multiply($hundred, '10');
        $this->assertEquals('100', $hundred);
        $this->assertEquals('1000', $thousand);
        $this->assertEquals('100', $this->manager->divide($thousand, '10'));
        $this->assertEquals('1', $this->manager->divide('1', '1'));
        $this->assertEquals('0', $this->manager->mod('23', '23'));
        $this->assertEquals('23', $this->manager->mod('23', '233'));
        $this->assertEquals('120', $this->manager->factorial('5'));
        $this->assertEquals('720', $this->manager->factorial('6'));
    }
}