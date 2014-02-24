<?php namespace Stillat\Common\Database\Tenant\SchemaCreator;

interface SchemaCreatorInterface {


	/**
	 * Creates a new database schema.
	 *
	 * @param  string $schemaName
	 * @return bool
	 */
	public function createSchema($schemaName);

	/**
	 * Drops a given database schema.
	 *
	 * @param  string $schemaName
	 * @return bool
	 */
	public function dropSchema($schemaName);

}