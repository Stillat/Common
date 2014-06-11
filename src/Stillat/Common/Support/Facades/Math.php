<?php namespace Stillat\Common\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see  \Stillat\Common\Math\MathManager
 * @see  \Stillat\Common\Math\ExpressionEngines\ExpressionEngineInterface
 */
class Math extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	public static function getFacadeAccessor()
	{
		return 'stillat-common.mathmanager';
	}

}

