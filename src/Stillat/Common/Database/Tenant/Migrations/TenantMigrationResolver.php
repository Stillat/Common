<?php namespace Stillat\Common\Database\Tenant\Migrations;

class TenantMigrationResolver {


	/**
	 * Resolve a migration class name from a file.
	 *
	 * @param  string  $file
	 * @return string
	 */
	public function resolveMigrationName($file)
	{
		$file = implode('_', array_slice(explode('_', $file), 4));

		$class = studly_case($file);

		return $class;
	}

	

}