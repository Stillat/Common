<?php

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