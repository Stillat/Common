<?php

namespace Stillat\Common\Contracts\Math;

interface ExpressionEngineInterface
{

    /**
     * Sets the precision to use in calculations
     *
     * @param int $precision The precision to use
     */
    public function setPrecision($precision);

    /**
     * Gets the precision used in calculations
     *
     * @return int The precision used in calculations
     */
    public function getPrecision();

    /**
     * Returns the absolute value of a number.
     *
     * @param  float $number
     *
     * @return float|int
     */
    public function abs($number);

    /**
     * Returns the arc cosine of a number.
     *
     * @param  float $number
     *
     * @return float The arc cosine of a number in radians.
     */
    public function acos($number);

    /**
     * Returns the arc sine of a number.
     *
     * @param  float $number
     *
     * @return float The arc sine of a number in radians.
     */
    public function asin($number);

    /**
     * Returns the arc tangent of a number.
     *
     * @param  float $number
     *
     * @return float The arc tangent of a number in radians.
     */
    public function atan($number);

    /**
     * Calculates the arc tangent of two variables.
     *
     * @param  float $x
     * @param  float $y
     *
     * @return float
     */
    public function atan2($x, $y);

    /**
     * Returns the next highest integer of a number.
     *
     * @param  number $number
     *
     * @return int
     */
    public function ceiling($number);

    /**
     * Returns the cosine of the specified angle.
     *
     * @param  float $angle
     *
     * @return float
     */
    public function cos($angle);

    /**
     * Returns the hyperbolic cosine of the angle.
     *
     * @param  float $angle
     *
     * @return float
     */
    public function cosh($angle);

    /**
     * Returns e raised to a given power.
     *
     * @param  float $number
     *
     * @return double
     */
    public function exp($number);

    /**
     * Returns the next lowest integer of a number.
     *
     * @param  float $number
     *
     * @return int
     */
    public function floor($number);

    /**
     * Returns the logarithm of a number in a specified base.
     *
     * @param  double $number
     * @param  double $base optional
     *
     * @return double
     */
    public function log($number, $base = M_E);

    /**
     * Returns the base-10 logarithm of a number.
     *
     * @param  double $number
     *
     * @return double
     */
    public function log10($number);

    /**
     * Returns the highest value in the array of numbers.
     *
     * @param  array $numbers
     *
     * @return number|mixed
     */
    public function max(array $numbers);

    /**
     * Returns the lowest value in the array of numbers.
     *
     * @param  array $numbers
     *
     * @return number|mixed
     */
    public function min(array $numbers);

    /**
     * Returns a base number raised to an exponent.
     *
     * @param  $base     number
     * @param  $exponent number
     *
     * @return float
     */
    public function pow($base, $exponent);

    /**
     * Rounds a number to the nearest value.
     *
     * @param float $number
     * @param int   $precision optional The number of digits to round to.
     * @param int   $mode      optional The rounding mode.
     *
     * @return number|mixed
     */
    public function round($number, $precision = 0, $mode = PHP_ROUND_HALF_UP);

    /**
     * Returns a value indicating the sign of a number.
     *
     * @param  number|mixed $number
     *
     * @return int
     */
    public function sign($number);

    /**
     * Returns the sine of the given angle.
     *
     * @param  number|mixed $angle The angle in radians.
     *
     * @return number|mixed
     */
    public function sin($angle);

    /**
     * Returns the hyperbolic sine of of the angle.
     *
     * @param  number|mixed $angle
     *
     * @return number|mixed
     */
    public function sinh($angle);

    /**
     * Returns the square root of a given number.
     *
     * @param  number|mixed $number
     *
     * @return number|mixed
     */
    public function sqrt($number);

    /**
     * Returns the tangent of a specified angle.
     *
     * @param  number|mixed $angle The angle in radians.
     *
     * @return number|mixed
     */
    public function tan($angle);

    /**
     * Returns the hyperbolic tangent of an angle.
     *
     * @param  number|mixed $angle
     *
     * @return number|mixed
     */
    public function tanh($angle);

    /**
     * Returns the integral part of a given number.
     *
     * @param  float $number
     *
     * @return int|0 on failure
     */
    public function truncate($number);

    /**
     * Returns the sum of two numbers.
     *
     * @param  number|mixed $numberOne
     * @param  number|mixed $numberTwo
     *
     * @return number|mixed
     */
    public function add($numberOne, $numberTwo);

    /**
     * Returns the difference of two numbers.
     *
     * @param  number|mixed $numberOne
     * @param  number|mixed $numberTwo
     *
     * @return number|mixed
     */
    public function subtract($numberOne, $numberTwo);

    /**
     * Returns the product of two numbers.
     *
     * @param  number|mixed $numberOne
     * @param  number|mixed $numberTwo
     *
     * @return number|mixed
     */
    public function multiply($numberOne, $numberTwo);

    /**
     * Returns the quotient of two numbers.
     *
     * @param  number|mixed $numberOne
     * @param  number|mixed $numberTwo
     *
     * @return number|mixed
     */
    public function divide($numberOne, $numberTwo);

    /**
     * Returns the remainder of two numbers.
     *
     * @param  number|mixed $numberOne
     * @param  number|mixed $numberTwo
     *
     * @return number|mixed
     */
    public function mod($numberOne, $numberTwo);

    /**
     * Calculates the factorial of a number.
     *
     * @param  number|mixed $number
     *
     * @return number|mixed
     */
    public function factorial($number);

}