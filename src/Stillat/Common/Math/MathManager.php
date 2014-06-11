<?php namespace Stillat\Common\Math;

use Stillat\Common\Exceptions\InvalidArgumentException;
use Stillat\Common\Math\ExpressionEngines\ExpressionEngineInterface;

class MathManager {
	
	/**
	 * The expression engine implementation.
	 * 
	 * @var \Stillat\Common\Math\ExpressionEngines\ExpressionEngineInterface
	 */
	protected $expressionEngine = null;

	/**
	 * Maps the math expression engine names to their class names.
	 * 
	 * @var array
	 */
	protected $driverClassMap = array(
		'bc' => '\Stillat\Common\Math\ExpressionEngines\BinaryCaclulatorExpressionEngine',
		);

	/**
	 * Creates a new instance of MathManager
	 * 
	 * @param   string|ExpressionEngineInterface $driver The engine name/implementation
	 * @return  Stillat\Common\Math\MathManager
	 */
	public function __construct($driver)
	{
		if (is_object($driver) && $driver instanceof ExpressionEngineInterface)
		{
			$this->expressionEngine = $driver;
			return;
		}
		else
		{
			if (is_string($driver) && (isset($this->driverClassMap[$driver]) === false))
			{
				throw new InvalidArgumentException("Math expression engine '{$driver}' is not supported.");
			}
			else
			{
				$this->expressionEngine = new $this->driverClassMap[$driver];
			}
		}
	}

	/**
	 * Returns the ExpressionEngineInterface implementation
	 * 
	 * @return \Stillat\Common\Math\ExpressionEngines\ExpressionEngineInterface
	 */
	public function getDriver()
	{
		return $this->expressionEngine;
	}

	/**
	 * Sets the precision to use in calculations
	 * 
	 * @param int $precision The precision to use
	 */
	public function setPrecision($precision)
	{
		$this->expressionEngine->setPrecision($precision);

		return $this;
	}

	/**
	 * Returns the absolute value of a number.
	 *
	 * @param  float $number
	 * @return float|int
	 */
	public function abs($number)
	{
		return $this->expressionEngine->abs($number);
	}

	/**
	 * Returns the arc cosine of a number.
	 *
	 * @param  float $number
	 * @return float The arc cosine of a number in radians.
	 */
	public function acos($number)
	{
		return $this->expressionEngine->acos($number);
	}

	/**
	 * Returns the arc sine of a number.
	 *
	 * @param  float $number
	 * @return float The arc sine of a number in radians.
	 */
	public function asin($number)
	{
		return $this->expressionEngine->asin($number);
	}

	/**
	 * Returns the arc tangent of a number.
	 *
	 * @param  float $number
	 * @param  float $y      optional
	 * @return float The arc tangent of a number in radians.
	 */
	public function atan($number)
	{
		return $this->expressionEngine->atan($number);
	}

	/**
	 * Calculates the arc tangent of two variables.
	 *
	 * @param  float $x
	 * @param  float $y
	 * @return float
	 */
	public function atan2($x, $y)
	{
		return $this->expressionEngine->atan2($x, $y);
	}

	/**
	 * Returns the next highest integer of a number.
	 *
	 * @return int
	 */
	public function ceiling($number)
	{
		return $this->expressionEngine->ceiling($number);
	}

	/**
	 * Returns the cosine of the specified angle.
	 *
	 * @param  float $angle
	 * @return float
	 */
	public function cos($angle)
	{
		return $this->expressionEngine->cos($angle);
	}

	/**
	 * Returns the hyperbolic cosine of the angle.
	 *
	 * @param  float $angle
	 * @return float
	 */
	public function cosh($angle)
	{
		return $this->expressionEngine->cosh($angle);
	}

	/**
	 * Returns e raised to a given power.
	 *
	 * @param  float $number
	 * @return double
	 */
	public function exp($number)
	{
		return $this->expressionEngine->exp($number);
	}

	/**
	 * Returns the next lowest integer of a number.
	 *
	 * @param  float $number
	 * @return int
	 */
	public function floor($number)
	{
		return $this->expressionEngine->floor($number);
	}

	/**
	 * Returns the logarithm of a number in a specified base.
	 *
	 * @param  double $number
	 * @param  double $base optional
	 * @return double
	 */
	public function log($number, $base = M_E)
	{
		return $this->expressionEngine->log($number, $base = M_E);
	}

	/**
	 * Returns the base-10 logarithm of a number.
	 *
	 * @param  double $number
	 * @return double
	 */
	public function log10($number)
	{
		return $this->expressionEngine->log10($number);
	}

	/**
	 * Returns the highest value in the array of numbers.
	 *
	 * @param  array $numbers
	 * @return number|mixed
	 */
	public function max(array $numbers)
	{
		return $this->expressionEngine->max($numbers);
	}

	/**
	 * Returns the lowest value in the array of numbers.
	 *
	 * @param  array $numbers
	 * @return number|mixed
	 */
	public function min(array $numbers)
	{
		return $this->expressionEngine->min($numbers);
	}

	/**
	 * Returns a base number raised to an exponent.
	 *
	 * @param  $base     number
	 * @param  $exponent number
	 * @return float
	 */
	public function pow($base, $exponent)
	{
		return $this->expressionEngine->pow($base, $exponent);

	}

	/**
	 * Rounds a number to the nearest value.
	 *
	 * @param float $number
	 * @param int   $precision optional The number of digits to round to.
	 * @param int   $mode      optional The rounding mode.
	 * @return number|mixed
	 */
	public function round($number, $precision = 0, $mode = PHP_ROUND_HALF_UP)
	{
		return $this->expressionEngine->round($number, $precision = 0, $mode = PHP_ROUND_HALF_UP);
	}

	/**
	 * Returns a value indicating the sign of a number.
	 *
	 * @param  number|mixed $number
	 * @return int
	 */
	public function sign($number)
	{
		return $this->expressionEngine->sign($number);
	}

	/**
	 * Returns the sine of the given angle.
	 *
	 * @param  number|mixed $angle The angle in radians.
	 * @return number|mixed 
	 */
	public function sin($angle)
	{
		return $this->expressionEngine->sin($angle);
	}

	/**
	 * Returns the hyperbolic sine of of the angle.
	 *
	 * @param  number|mixed $angle
	 * @return number|mixed
	 */
	public function sinh($angle)
	{
		return $this->expressionEngine->sinh($angle);
	}

	/**
	 * Returns the square root of a given number.
	 *
	 * @param  number|mixed $number
	 * @return number|mixed
	 */
	public function sqrt($number)
	{
		return $this->expressionEngine->sqrt($number);
	}

	/**
	 * Returns the tangent of a specified angle.
	 *
	 * @param  number|mixed $angle The angle in radians.
	 * @return number|mixed
	 */
	public function tan($angle)
	{
		return $this->expressionEngine->tan($angle);
	}

	/**
	 * Returns the hyperbolic tangent of an angle.
	 *
	 * @param  number|mixed $angle
	 * @return number|mixed
	 */
	public function tanh($angle)
	{
		return $this->expressionEngine->tanh($angle);
	}

	/**
	 * Returns the integral part of a given number.
	 *
	 * @param  float $number
	 * @return int|0 on failure
	 */
	public function truncate($number)
	{
		return $this->expressionEngine->truncate($number);
	}

	/**
	 * Returns the sum of two numbers.
	 *
	 * @param  number|mixed $numberOne
	 * @param  number|mixed $numberTwo
	 * @return number|mixed
	 */
	public function add($numberOne, $numberTwo)
	{
		return $this->expressionEngine->add($numberOne, $numberTwo);
	}

	/**
	 * Returns the difference of two numbers.
	 *
	 * @param  number|mixed $numberOne
	 * @param  number|mixed $numberTwo
	 * @return number|mixed
	 */
	public function subtract($numberOne, $numberTwo)
	{
		return $this->expressionEngine->subtract($numberOne, $numberTwo);
	}

	/**
	 * Returns the product of two numbers.
	 *
	 * @param  number|mixed $numberOne
	 * @param  number|mixed $numberTwo
	 * @return number|mixed
	 */
	public function multiply($numberOne, $numberTwo)
	{
		return $this->expressionEngine->multiply($numberOne, $numberTwo);
	}

	/**
	 * Returns the quotient of two numbers.
	 *
	 * @param  number|mixed $numberOne
	 * @param  number|mixed $numberTwo
	 * @return number|mixed
	 */
	public function divide($numberOne, $numberTwo)
	{
		return $this->expressionEngine->divide($numberOne, $numberTwo);
	}

	/**
	 * Returns the remainder of two numbers.
	 *
	 * @param  number|mixed $numberOne
	 * @param  number|mixed $numberTwo
	 * @return number|mixed
	 */
	public function mod($numberOne, $numberTwo)
	{
		return $this->expressionEngine->mod($numberOne, $numberTwo);
	}

	/**
	 * Calculates the factorial of a number.
	 *
	 * @param  number|mixed $number
	 * @return number|mixed
	 */
	public function factorial($number)
	{
		return $this->expressionEngine->factorial($number);
	}

}