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
        $this->addToHistory($func, [$number, $numberTwo]);
        $this->setCurrentValue($this->expressionEngine->{$this->currentOperation}($this->getCurrentValue(),
            $this->expressionEngine->{$func}($number, $numberTwo)));
    }

    protected function applyFunctionOnExpressionEngine3Param($func, $number, $numberTwo, $numberThree)
    {
        $this->addToHistory($func, [$number, $numberTwo, $numberThree]);
        $this->setCurrentValue($this->expressionEngine->{$this->currentOperation}($this->getCurrentValue(),
            $this->expressionEngine->{$func}($number, $numberTwo, $numberThree)));
    }

    protected function internalSetOperationModeWithHistory($mode)
    {
        $this->currentOperation = $mode;
        $this->addGroupOperationToHistory($mode);
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
            $this->internalSetOperationModeWithHistory('add');
        } elseif ($number instanceof Closure) {
            $this->internalSetOperationModeWithHistory('add');
            return $this->group($number);
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
            $this->internalSetOperationModeWithHistory('subtract');
        } elseif($number instanceof Closure) {
            $this->internalSetOperationModeWithHistory('subtract');
            return $this->group($number);
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
            $this->internalSetOperationModeWithHistory('multiply');
        } elseif ($number instanceof Closure) {
            $this->internalSetOperationModeWithHistory('multiply');
            return $this->group($number);
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
     * @throws \Stillat\Common\Exceptions\Arithmetic\DivideByZeroException;
     * @return $this
     */
    public function divide($number = null)
    {
        if ($number === null) {
            $this->internalSetOperationModeWithHistory('divide');
        } elseif ($number instanceof Closure) {
            $this->internalSetOperationModeWithHistory('divide');
            return $this->group($number);
        } else {
            $this->performOperationOnExpressionEngine('divide', $number);
        }

        return $this;
    }

    protected function reduceValue(&$value)
    {
        if ($value instanceof Closure) {
            $value = $value($this->getPristineCopy());
            if ($value instanceof FluentCalculator) {
                $value = $value->get();
            }
        }
        return $this;
    }

    protected function runExpressionFunction($func, $number)
    {
        $this->reduceValue($number);
        $this->applyFunctionOnExpressionEngine($func, $number);

        return $this;
    }

    protected function runExpressionFunction2Param($func, $number, $numberTwo)
    {
        $this->reduceValue($number)->reduceValue($numberTwo);
        $this->applyFunctionOnExpressionEngine2Param($func, $number, $numberTwo);

        return $this;
    }

    protected function runExpressionFunction3Param($func, $number, $numberTwo, $numberThree)
    {
        $this->reduceValue($number)->reduceValue($numberTwo)->reduceValue($numberThree);
        $this->applyFunctionOnExpressionEngine3Param($func, $number, $numberTwo, $numberThree);

        return $this;
    }

    /**
     * Returns the absolute value of a number.
     *
     * @param  float|Closure $number
     *
     * @return $this
     */
    public function abs($number)
    {
        return $this->runExpressionFunction('abs', $number);
    }

    /**
     * Returns the arc cosine of a number.
     *
     * @param  float|Closure $number
     *
     * @return $this
     */
    public function acos($number)
    {
        return $this->runExpressionFunction('acos', $number);
    }

    /**
     * Returns the arc sine of a number.
     *
     * @param  float|Closure $number
     *
     * @return $this
     */
    public function asin($number)
    {
        return $this->runExpressionFunction('asin', $number);
    }

    /**
     * Returns the arc tangent of a number.
     *
     * @param  float|Closure $number
     *
     * @return $this
     */
    public function atan($number)
    {
        return $this->runExpressionFunction('atan', $number);
    }

    /**
     * Calculates the arc tangent of two variables.
     *
     * @param  float|Closure $x
     * @param  float|Closure $y
     *
     * @return $this
     */
    public function atan2($x, $y)
    {
        return $this->runExpressionFunction2Param('atan2', $x, $y);
    }

    /**
     * Returns the cosine of the specified angle.
     *
     * @param  float|Closure $angle
     *
     * @return $this
     */
    public function cos($angle)
    {
        return $this->runExpressionFunction('cos', $angle);
    }

    /**
     * Returns the hyperbolic cosine of the angle.
     *
     * @param  float|Closure $angle
     *
     * @return $this
     */
    public function cosh($angle)
    {
        return $this->runExpressionFunction('cosh', $angle);
    }

    /**
     * Returns e raised to a given power.
     *
     * @param  float|Closure $number
     *
     * @return $this
     */
    public function exp($number)
    {
        return $this->runExpressionFunction('exp', $number);
    }

    /**
     * Returns the logarithm of a number in a specified base.
     *
     * @param  double|Closure $number
     * @param  double|Closure $base optional
     *
     * @return $this
     */
    public function log($number, $base = M_E)
    {
        return $this->runExpressionFunction2Param('log', $number, $base);
    }

    /**
     * Returns a base number raised to an exponent.
     *
     * @param  $number |Closure     number
     * @param  $exponent |Closure   number
     *
     * @return $this
     */
    public function pow($number, $exponent)
    {
        return $this->runExpressionFunction2Param('pow', $number, $exponent);
    }

    /**
     * Returns the sine of the given angle.
     *
     * @param  number|mixed|Closure $angle The angle in radians.
     *
     * @return $this
     */
    public function sin($angle)
    {
        return $this->runExpressionFunction('sin', $angle);
    }

    /**
     * Returns the hyperbolic sine of of the angle.
     *
     * @param  number|mixed|Closure $angle
     *
     * @return $this
     */
    public function sinh($angle)
    {
        return $this->runExpressionFunction('sinh', $angle);
    }

    /**
     * Returns the square root of a given number.
     *
     * @param  number|mixed|Closure $number
     *
     * @return $this
     */
    public function sqrt($number)
    {
        return $this->runExpressionFunction('sqrt', $number);
    }

    /**
     * Returns the tangent of a specified angle.
     *
     * @param  number|mixed|Closure $angle The angle in radians.
     *
     * @return $this
     */
    public function tan($angle)
    {
        return $this->runExpressionFunction('tan', $angle);
    }

    /**
     * Returns the hyperbolic tangent of an angle.
     *
     * @param  number|mixed|Closure $angle
     *
     * @return $this
     */
    public function tanh($angle)
    {
        return $this->runExpressionFunction('tanh', $angle);
    }

    /**
     * Returns the remainder of two numbers.
     *
     * @param  number|mixed|Closure $numberOne
     * @param  number|mixed|Closure $numberTwo
     *
     * @return $this
     */
    public function mod($numberOne, $numberTwo)
    {
        return $this->runExpressionFunction2Param('mod', $numberOne, $numberTwo);
    }

    /**
     * Calculates the factorial of a number.
     *
     * @param  number|mixed|Closure $number
     *
     * @return $this
     */
    public function factorial($number)
    {
        return $this->runExpressionFunction('factorial', $number);
    }

    /**
     * Returns the next lowest integer of a number.
     *
     * @param  float|Closure $number
     *
     * @return $this
     */
    public function floor($number)
    {
        return $this->runExpressionFunction('floor', $number);
    }

    /**
     * Returns the base-10 logarithm of a number.
     *
     * @param  double|Closure $number
     *
     * @return $this
     */
    public function log10($number)
    {
        return $this->runExpressionFunction('log10', $number);
    }

    /**
     * Returns the highest value in the array of numbers.
     *
     * @param  array|Closure $numbers
     *
     * @return $this
     */
    public function max(array $numbers)
    {
        return $this->runExpressionFunction('max', $numbers);
    }

    /**
     * Returns the lowest value in the array of numbers.
     *
     * @param  array|Closure $numbers
     *
     * @return $this
     */
    public function min(array $numbers)
    {
        return $this->runExpressionFunction('min', $numbers);
    }

    /**
     * Rounds a number to the nearest value.
     *
     * @param float|Closure $number
     * @param int|Closure $precision optional The number of digits to round to.
     * @param int|Closure $mode optional The rounding mode.
     *
     * @return $this
     */
    public function round($number, $precision = 0, $mode = PHP_ROUND_HALF_UP)
    {
        return $this->runExpressionFunction3Param('round', $number, $precision, $mode);
    }

    /**
     * Returns the next highest integer of a number.
     *
     * @param  number|Closure $number
     *
     * @return $this
     */
    public function ceiling($number)
    {
        return $this->runExpressionFunction('ceiling', $number);
    }

    /**
     * Returns the integral part of a given number.
     *
     * @param  float|Closure $number
     *
     * @return $this
     */
    public function truncate($number)
    {
        return $this->runExpressionFunction('truncate', $number);
    }


    public function __toString()
    {
        return (string)($this->getCurrentValue());
    }


}