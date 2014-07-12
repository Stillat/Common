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

if (!function_exists('array_key_exists_then_or'))
{
	/**
	 * Returns a different value when an array key exists, or doesn't.
	 * 
	 * 
	 * @param  array            $array The array to check in.
	 * @param  string           $check The key to check for.
	 * @param  \Closure|mixed   $then  The value to return if the key exists.
	 * @param  \Closure|mixed   $or    The value to return if the key does not exist.
	 * @return mixed
	 */
	function array_key_exists_then_or(array $array, $check, $then = true, $or = false)
	{
		if (array_key_exists($check, $array))
		{
			if (is_object($then) and $then instanceof Closure) { return $then(); }

			return $then;
		}

		if (is_object($or) and $or instanceof Closure) { return $or(); }

		return $or;
	}
}

if (!function_exists('if_null_then'))
{
	/**
	 * Returns a value based on whether another value is null.
	 *
	 * If you need to check if a value exists in an array, such as a checkbox
	 * input, consider using the `array_key_exists_then_or` for a simple inline
	 * approach.
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