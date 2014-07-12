<?php

if (!function_exists('d'))
{
	/**
	 * Dumps the passed variables.
	 * 
	 * @param   dynamic mixed
	 * @return  void
	 */
	function d()
	{
		array_map(function($x) { var_dump($x); }, func_get_args ());
	}
}

if (!function_exists('if_null_then'))
{
	/**
	 * Returns a value based on whether another value is null.
	 *
	 * Concerning arrays, if you need to check that a value has
	 * been set (which is not necessarily the same is being
	 * equal to null), consider using the `isset_or` function
	 * instead.
	 * 
	 * @param  mixed          $check The value to check for null.
	 * @param  \Closure|mixed $then  The value to return if $check is null.
	 * @return mixed
	 */
	function if_null_then($check, $then)
	{
		if ($check === null)
		{
			if (is_object($then) and $then instanceof Closure) { return $then(); }

			return $then;
		}

		return $check;
	}
}

if (!function_exists('can_be_valid_string'))
{

	/**
	 * Tests whether a given input can be considered a string.
	 *
	 * @param  mixed $input
	 * @param  bool  $allowArrays
	 * @return bool
	 */
	function can_be_valid_string($input, $allowArrays = false)
	{
		if (is_null($input))
		{
			return false;
		}

		if (is_object($input) and method_exists($input, '__toString'))
		{
			return true;
		}

		// Even though arrays are not strings, you can adjust the function
		// to allow arrays. This is useful for passing arrays of strings.
		if ($allowArrays and is_array($input))
		{
			return true;
		}

		if (!is_scalar($input))
		{
			return false;
		}

		return true;
	}

}