<?php namespace Stillat\Common\Database;

use LaravelBook\Ardent\Ardent;

abstract class Model extends Ardent {

	/**
	 * Returns the next record.
	 *
	 * @return Stillat\Common\Database\Model
	 */
	public function next()
	{
		return static::where($this->getKeyName(), '>', $this->getKey())->min('id');
	}

	/**
	 * Returns the previous record.
	 *
	 * @return Stillat\Common\Database\Model
	 */
	public function previous()
	{
		return static::where($this->getKeyName(), '<', $this->getKey())->max('id');
	}

}