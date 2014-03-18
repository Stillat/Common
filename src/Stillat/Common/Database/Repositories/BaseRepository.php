<?php namespace Stillat\Common\Database\Repositories;

use Stillat\Common\Database\Repositories\RepositoryInterface;
use DB;

class BaseRepository implements RepositoryInterface {

	/**
	 * The table for the repository.
	 *
	 * @var string
	 */
	protected $tableName = '';

	/**
	 * Indicates whether safe deleting is enabled.
	 *
	 * @var bool
	 */
	protected $safeDeletes = true;

	/**
	 * The name of the primary key column.
	 *
	 * @var string
	 */
	protected $primaryKeyColumn = 'id';

	/**
	 * Create a new repository instance.
	 *
	 *
	 */
	public function __construct()
	{

	}

	/**
	 * Get the table name for the repository.
	 *
	 * @return string
	 */
	public function getTable()
	{
		return $this->tableName;
	}

	/**
	 * Retrieve safe delete setting.
	 *
	 * @return boolean
	 */
	public function getSafeDeleteEnabled()
	{
		return $this->safeDeletes;
	}

	/**
	 * Retrieve the maximum value of a given column.
	 *
	 * @param  string  $column
	 * @return mixed
	 */
	public function max($column)
	{
		return DB::table($this->tableName)->whereNull('deleted_at')->max($column);
	}

	/**
	 * Retrieve the minimum value of a given column.
	 *
	 * @param  string  $column
	 * @return mixed
	 */
	public function min($column)
	{
		return DB::table($this->tableName)->whereNull('deleted_at')->min($column);
	}

	/**
	 * Retrieve the average of the values of a given column.
	 *
	 * @param  string  $column
	 * @return mixed
	 */
	public function avg($column)
	{
		return DB::table($this->tableName)->whereNull('deleted_at')->avg($column);
	}

	/**
	 * Retrieve the sum of the values of a given column.
	 *
	 * @param  string  $column
	 * @return mixed
	 */
	public function sum($column)
	{
		return DB::table($this->tableName)->whereNull('deleted_at')->sum($column);
	}

	/**
	 * Increment a column's value by a given amount.
	 *
	 * @param  string  $column
	 * @param  int     $amount
	 * @param  array   $extra
	 * @return int
	 */
	public function increment($column, $interval = 1, $additional = array())
	{
		return DB::table($this->tableName)->whereNull('deleted_at')->increment($column, $inteval, $additional);
	}

	/**
	 * Decrement a column's value by a given amount.
	 *
	 * @param  string  $column
	 * @param  int     $amount
	 * @param  array   $extra
	 * @return int
	 */
	public function decrement($column, $interval = 1, $additional = array())
	{
		return DB::table($this->tableName)->whereNull('deleted_at')->decrement($column, $inteval, $additional);
	}

	/**
	 * Insert a new record into the database.
	 *
	 * @param  array  $values
	 * @return bool
	 */
	public function insert(array $values)
	{
		return DB::table($this->tableName)->insertGetID($values);
	}

	/**
	 * Delete a record from the database.
	 *
	 * @param  mixed  $id
	 * @return int
	 */
	public function delete()
	{
		if ($this->safeDeletes)
		{
			return;
		}
		else
		{
			return DB::table($this->tableName)->delete();
		}
	}

	public function table()
	{
		return DB::table($this->tableName);
	}

	/**
	 * Returns the total number of records.
	 *
	 * @return int
	 */
	public function count()
	{
		return DB::table($this->tableName)->count('*');
	}

	/**
	 * Returns an array of primary keys.
	 *
	 * @return array
	 */
	public function getPrimaryKeysAsArray()
	{
		$primaryKeys = array();

		$queryData = DB::table($this->tableName)->select($this->primaryKeyColumn)->get();

		foreach($queryData as $primaryKey)
		{
			$primaryKeys[] = $primaryKey->{$this->primaryKeyColumn};
		}

		return $primaryKeys;
	}

}