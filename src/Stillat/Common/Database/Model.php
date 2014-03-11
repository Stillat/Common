<?php namespace Stillat\Common\Database;

use Stillat\Common\Validation\ValidationObjectInterface;
use LaravelBook\Ardent\Ardent;

abstract class Model extends Ardent implements ValidationObjectInterface {

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