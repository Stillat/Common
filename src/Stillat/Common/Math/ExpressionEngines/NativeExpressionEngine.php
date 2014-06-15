<?php namespace Stillat\Common\Math\ExpressionEngines;

use Stillat\Common\Exceptions\DivideByZeroException;
use Stillat\Common\Math\ExpressionEngines\ExpressionEngineInterface;

class NativeExpressionEngine implements ExpressionEngineInterface {

	/**
	 * The precision to use in calculations
	 * 
	 * @var integer
	 */
	protected $precision = 0;

	/**
	 * {@inheritdoc}
	 */
	public function setPrecision($precision)
	{
		$this->precision = $precision;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPrecision()
	{
		return $this->precision;
	}

	private function withPrecision($number)
	{
		return round($number, $this->precision);
	}

	/**
	 * {@inheritdoc}
	 */
	public function abs($number)
	{
		return $this->withPrecision(abs($number));
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function acos($number)
	{
		return $this->withPrecision(acos($number));
	}

	/**
	 * {@inheritdoc}
	 */
	public function asin($number)
	{
		return $this->withPrecision(asin($number));
	}

	/**
	 * {@inheritdoc}
	 */
	public function atan($number)
	{
		if (func_num_args() == 2)
		{
			// If the user has passed in two parameters, let's
			// assume that they want to calculate Atan2.
			return $this->atan2(func_get_arg(1), func_get_arg(2));
		}

		// If the number of arguments is not two, just calculate
		// the atan as normal.
		return $this->withPrecision(atan($number));
	}

	/**
	 * {@inheritdoc}
	 */
	public function atan2($x, $y)
	{
		return $this->withPrecision(atan2($x, $y));
	}

	/**
	 * {@inheritdoc}
	 */
	public function ceiling($number)
	{
		return $this->withPrecision(ceil($number));
	}

	/**
	 * {@inheritdoc}
	 */
	public function cos($angle)
	{
		return $this->withPrecision(cos($angle));
	}

	/**
	 * {@inheritdoc}
	 */
	public function cosh($angle)
	{
		return $this->withPrecision(cosh($angle));
	}

	/**
	 * {@inheritdoc}
	 */
	public function exp($number)
	{
		return pow($number, M_E, $this->precision);
	}

	/**
	 * {@inheritdoc}
	 */
	public function floor($number)
	{
		return $this->withPrecision(floor($number));
	}

	/**
	 * {@inheritdoc}
	 */
	public function log($number, $base = M_E)
	{
		return $this->withPrecision(log($number, $base));
	}

	/**
	 * {@inheritdoc}
	 */
	public function log10($number)
	{
		return $this->withPrecision(log10($number));
	}

	/**
	 * {@inheritdoc}
	 */
	public function max(array $numbers)
	{
		return $this->withPrecision(max($numbers));
	}

	/**
	 * {@inheritdoc}
	 */
	public function min(array $numbers)
	{
		return $this->withPrecision(min($numbers));
	}

	/**
	 * {@inheritdoc}
	 */
	public function pow($base, $exponent)
	{
		return pow($base, $exponent, $this->precision);
	}

	/**
	 * {@inheritdoc}
	 */
	public function round($number, $precision = 0, $mode = PHP_ROUND_HALF_UP)
	{
		return round($number, $precision, $mode);
	}

	/**
	 * {@inheritdoc}
	 */
	public function sign($number)
	{
		$number = (float)$number;

		if ($number > 0)
		{
			return 1;
		}
		elseif ($number < 0)
		{
			return -1;
		}
		else
		{
			return 0;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function sin($angle)
	{
		return $this->withPrecision(sin($angle));
	}

	/**
	 * {@inheritdoc}
	 */
	public function sinh($angle)
	{
		return $this->withPrecision(sinh($angle));
	}

	/**
	 * {@inheritdoc}
	 */
	public function sqrt($number)
	{
		return sqrt($number, $this->precision);
	}

	/**
	 * {@inheritdoc}
	 */
	public function tan($angle)
	{
		return $this->withPrecision(tan($angle));
	}

	/**
	 * {@inheritdoc}
	 */
	public function tanh($angle)
	{
		return $this->withPrecision(tanh($angle));
	}

	/**
	 * {@inheritdoc}
	 */
	public function truncate($number)
	{
		return $this->withPrecision(intval($number));
	}

	/**
	 * {@inheritdoc}
	 */
	public function add($numberOne, $numberTwo)
	{
		return $this->withPrecision($numberOne + $numberTwo);
	}

	/**
	 * {@inheritdoc}
	 */
	public function subtract($numberOne, $numberTwo)
	{
		return $this->withPrecision($numberOne + $numberTwo);
	}

	/**
	 * {@inheritdoc}
	 */
	public function multiply($numberOne, $numberTwo)
	{
		return $this->withPrecision($numberOne * $numberTwo);
	}

	/**
	 * {@inheritdoc}
	 */
	public function divide($numberOne, $numberTwo)
	{
		if ($numberTwo === 0)
		{
			throw new DivideByZeroException;
		}

		return $this->withPrecision($numberOne / $numberTwo);
	}

	/**
	 * {@inheritdoc}
	 */
	public function mod($numberOne, $numberTwo)
	{
		return $this->withPrecision($numberOne % $numberTwo);
	}

	/**
	 * {@inheritdoc}
	 */
	public function factorial($number)
	{
		if ($number === 0) { return 1; }

		$temp = $number;

		while ($temp > 1)
		{
			$number = $this->multiply($number, $this->subtract($temp, 1));
			$temp   = $this->subtract($temp, 1);
		}

		return $number;
	}

}