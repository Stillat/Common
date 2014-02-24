<?php namespace Stillat\Common\Facades;

use Illuminate\Support\Facades\Facade;

class Downloader extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'downloader'; }
	
}