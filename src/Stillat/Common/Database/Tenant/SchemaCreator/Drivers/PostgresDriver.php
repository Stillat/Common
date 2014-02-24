<?php namespace Stillat\Common\Database\Tenant\SchemaCreator\Drivers;

use Stillat\Common\Database\Tenant\SchemaCreator\Drivers\PDOSchemaDriver;

class PostgresDriver extends PDOSchemaDriver {

	protected $pdoDriver = 'pgsql';

	protected $createSyntax = 'CREATE DATABASE `:SCHEMA`;';

	protected $dropSyntax   = 'DROP DATABASE `:SCHEMA`;';
	
}