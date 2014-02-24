<?php namespace Stillat\Common\Facades;

use Illuminate\Support\Facades\Facade;

class Mime extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'Stillat\Common\Client\Mime\MimeTypeManager';
	}

	
}