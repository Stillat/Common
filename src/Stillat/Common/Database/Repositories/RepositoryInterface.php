<?php namespace Stillat\Common\Database\Repositories;

interface RepositoryInterface {

	/**
	 * Get the table name for the repository.
	 *
	 * @return string
	 */
	public function getTable();

	public function table();

	/**
	 * Retrieve safe delete setting.
	 *
	 * @return boolean
	 */
	public function getSafeDeleteEnabled();

	/**
	 * Retrieve the maximum value of a given column.
	 *
	 * @param  string  $column
	 * @return mixed
	 */
	public function max($column);

	/**
	 * Retrieve the minimum value of a given column.
	 *
	 * @param  string  $column
	 * @return mixed
	 */
	public function min($column);

	/**
	 * Retrieve the average of the values of a given column.
	 *
	 * @param  string  $column
	 * @return mixed
	 */
	public function avg($column);

	/**
	 * Retrieve the sum of the values of a given column.
	 *
	 * @param  string  $column
	 * @return mixed
	 */
	public function sum($column);

	/**
	 * Increment a column's value by a given amount.
	 *
	 * @param  string  $column
	 * @param  int     $amount
	 * @param  array   $extra
	 * @return int
	 */
	public function increment($column, $interval = 1, $additional = array());

	/**
	 * Decrement a column's value by a given amount.
	 *
	 * @param  string  $column
	 * @param  int     $amount
	 * @param  array   $extra
	 * @return int
	 */
	public function decrement($column, $interval = 1, $additional = array());

	/**
	 * Insert a new record into the database.
	 *
	 * @param  array  $values
	 * @return bool
	 */
	public function insert(array $values);

	/**
	 * Delete a record from the database.
	 *
	 * @param  mixed  $id
	 * @return int
	 */
	public function delete();

	/**
	 * Returns an array of primary keys.
	 *
	 * @return array
	 */
	public function getPrimaryKeysAsArray();
	
}