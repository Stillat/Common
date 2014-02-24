<?php namespace Stillat\Common\Database\Tenant\SchemaCreator\Drivers;

use Stillat\Common\Database\Tenant\SchemaCreator\Drivers\PDOSchemaDriver;

class MySqlDriver extends PDOSchemaDriver {

	protected $pdoDriver = 'mysql';

	protected $createSyntax = 'CREATE DATABASE IF NOT EXISTS `:SCHEMA`;';

	protected $dropSyntax   = 'DROP DATABASE IF EXISTS `:SCHEMA`;';
	
}