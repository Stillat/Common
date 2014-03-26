<?php namespace Stillat\Common\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see  \Stillat\Common\Collections\Sorting\SortingManager
 * @see  \Stillat\Common\Collections\Sorting\ArraySortingInterface
 */
class Sort extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	public static function getFacadeAccessor()
	{
		return 'stillat-common.sortmanager';
	}

}