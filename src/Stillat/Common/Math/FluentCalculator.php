<?php

namespace Stillat\Common\Math;

use Closure;
use BadMethodCallException;
use Collection\Collection;
use Stillat\Common\Contracts\Math\ExpressionEngineInterface;
use Stillat\Common\Exceptions\Argument\InvalidArgumentException;
use Stillat\Common\Traits\Expectations;

class FluentCalculator
{
    use Expectations;

    /**
     * The ExpressionEngineInterface implementation.
     *
     * @var \Stillat\Common\Contracts\Math\ExpressionEngineInterface
     */
    protected $expressionEngine;

    /**
     * Holds current operation values.
     *
     * @var \Collection\Collection
     */
    protected $operationValues;

    /**
     * The current running total name.
     *
     * @var string
     */
    protected $currentTotalName = 'default';

    protected $currentOperation = null;

    protected $currentFunction = null;

    protected $writeToHistory = true;

    public function __construct(ExpressionEngineInterface $expressionEngine)
    {
        $this->expressionEngine = $expressionEngine;
        $this->operationValues = new Collection;
        $this->addNewOperation('default');
    }

    protected function addNewOperation($operationName)
    {
        $this->operationValues[$operationName] = new OperationSequenceContainer($operationName, 0);
    }

    public function __call($method, $parameters)
    {
        if (strpos($method, 'with') === 0) {
            $operationName = substr($method, 3);
            return $this->with($operationName);
        }

        throw new BadMethodCallException("Method [$method] does not exist.");
    }

    public function with($operation)
    {
        if ($this->operationValues->has($operation) == false) {
            $this->addNewOperation($operation);
        }
        $this->currentTotalName = $operation;
        return $this;
    }

    public function get($operation = null)
    {
        $operationToRetrieve = strtolower(($operation == null) ? $this->currentTotalName : $operation);

        if ($this->operationValues->has($operationToRetrieve)) {
            return $this->operationValues[$operationToRetrieve]->value;
        }

        throw new InvalidArgumentException("Operation {$operationToRetrieve} has not been initialized.");
    }

    public function getHistory($operation = null)
    {
        $operationToRetrieve = strtolower(($operation == null) ? $this->currentTotalName : $operation);

        if ($this->operationValues->has($operationToRetrieve)) {
            return $this->operationValues[$operationToRetrieve]->getHistory();
        }

        throw new InvalidArgumentException("Operation {$operationToRetrieve} has not been initialized.");
    }

    protected function getCurrentValue()
    {
        return $this->operationValues[$this->currentTotalName]->value;
    }

    protected function setCurrentValue($value)
    {
        $this->operationValues[$this->currentTotalName]->value = $value;
    }

    protected function addToHistory($historyItemName, $historyItemValue)
    {
        if ($this->writeToHistory) {
            $this->operationValues[$this->currentTotalName]->appendHistory([$historyItemName, $historyItemValue]);
        }
    }

    protected function addGroupOperationToHistory($historyItemValue)
    {
        $this->addToHistory('group_operation', $historyItemValue);
    }

    /**
     * Resets the current instance.
     *
     * @return $this
     */
    public function reset()
    {
        $this->operationValues = new Collection;
        $this->addNewOperation('default');
        $this->currentTotalName = 'default';
        return $this;
    }

    /**
     * Gets a new copy of the current instance, and then resets it.
     *
     * @return \Stillat\Common\Math\FluentCalculator
     */
    protected function &getPristineCopy()
    {
        $newCopy = clone $this;
        $newCopy->reset();
        return $newCopy;
    }

    /**
     * Sets the precision to use.
     *
     * @param $precision
     *
     * @return $this
     */
    public function withPrecision($precision)
    {
        $this->expressionEngine->setPrecision($precision);
        return $this;
    }

    public function group(Closure $groupFunction)
    {
        $pristineCopy = $this->getPristineCopy();
        $groupFunction($pristineCopy);
        $value = $pristineCopy->get('default');
        $history = $pristineCopy->getHistory('default');
        $this->addToHistory('group', $history);
        $pristineCopy = null;
        unset($pristineCopy);

        $this->writeToHistory = false;
        $this->{$this->currentOperation}($value);
        $this->writeToHistory = true;

        return $this;
    }

    /**
     * Sets the current value.
     *
     * @param $number
     *
     * @return $this
     */
    public function set($number)
    {
        $this->setCurrentValue($number);
        $this->currentOperation = 'add';
        $this->addToHistory('set', $number);
        return $this;
    }

    /**
     * Helper function to recursively call a method on the FluentCalculator
     * and the underlying expression engine.
     *
     * @param $operation
     * @param $number
     */
    protected function performOperationOnExpressionEngine($operation, $number)
    {
        $this->currentOperation = $operation;
        if (is_array($number)) {
            foreach ($number as $actualNumber) {
                $this->{$operation}($actualNumber);
            }
        } else {
            $this->addToHistory($operation, $number);
            $this->setCurrentValue($this->expressionEngine->{$operation}($this->getCurrentValue(), $number));
        }
    }

    protected function applyFunctionOnExpressionEngine($func, $number)
    {
        $this->addToHistory($func, $number);
        $this->setCurrentValue($this->expressionEngine->{$this->currentOperation}($this->getCurrentValue(),
            $this->expressionEngine->{$func}($number)));
    }

    protected function applyFunctionOnExpressionEngine2Param($func, $number, $numberTwo)
    {
        $this->addToHistory($func, $number);
        $this->setCurrentValue($this->expressionEngine->{$this->currentOperation}($this->getCurrentValue(),
            $this->expressionEngine->{$func}($number, $numberTwo)));
    }

    /**
     * Adds a given number to the current value.
     *
     * @param $number
     *
     * @return $this
     */
    public function add($number = null)
    {
        if ($number == null) {
            $this->currentOperation = 'add';
            $this->addGroupOperationToHistory('add');
        } else {
            $this->performOperationOnExpressionEngine('add', $number);
        }
        return $this;
    }

    /**
     * Subtracts a given number from the current value.
     *
     * @param $number
     *
     * @return $this
     */
    public function subtract($number = null)
    {
        if ($number == null) {
            $this->currentOperation = 'subtract';
            $this->addGroupOperationToHistory('subtract');
        } else {
            $this->performOperationOnExpressionEngine('subtract', $number);
        }

        return $this;
    }

    /**
     * Multiplies the current value by a given number.
     *
     * @param $number
     *
     * @return $this
     */
    public function multiply($number = null)
    {
        if ($number === null) {
            $this->currentOperation = 'multiply';
            $this->addGroupOperationToHistory('multiply');
        } else {
            $this->performOperationOnExpressionEngine('multiply', $number);
        }

        return $this;
    }

    /**
     * Divides the current value by a given number.
     *
     * @param $number
     *
     * @throws \Stillat\Common\Exceptions\DivideByZeroException;
     * @return $this
     */
    public function divide($number = null)
    {
        if ($number === null) {
            $this->currentOperation = 'divide';
            $this->addGroupOperationToHistory('divide');
        } else {
            $this->performOperationOnExpressionEngine('divide', $number);
        }

        return $this;
    }

    protected function runExpressionFunction($func, $number)
    {
        if ($number == null) {
            $this->currentFunction = $func;
            $this->addGroupOperationToHistory($func);
        } else {
            $this->applyFunctionOnExpressionEngine($func, $number);
        }

        return $this;
    }

    protected function runExpressionFunction2Param($func, $number, $numberTwo)
    {
        if ($number == null) {
            $this->currentFunction = $func;
            $this->addGroupOperationToHistory($func);
        } else {
            $this->applyFunctionOnExpressionEngine2Param($func, $number, $numberTwo);
        }

        return $this;
    }

    public function abs($number = null)
    {
        return $this->runExpressionFunction('abs', $number);
    }

    public function acos($number = null)
    {
        return $this->runExpressionFunction('acos', $number);
    }

    public function asin($number = null)
    {
        return $this->runExpressionFunction('asin', $number);
    }

    public function atan($number = null)
    {
        return $this->runExpressionFunction('atan', $number);
    }

    public function atan2($x = null, $y = null)
    {
        $this->expectValuesWhenNotNull($x, [&$y], 'Dividend parameter required when divisor parameter present.');
        return $this->runExpressionFunction2Param('atan2', $x, $y);
    }


    public function __toString()
    {
        return (string)($this->getCurrentValue());
    }


}