<?php namespace Stillat\Common\Database\Tenant\SchemaCreator\Drivers;

use Stillat\Common\Database\Tenant\SchemaCreator\Drivers\PDOSchemaDriver;

class SqlServerDriver extends PDOSchemaDriver {

	protected $pdoDriver = 'sqlsrv';

	protected $createSyntax = 'CREATE DATABASE `:SCHEMA`;';

	protected $dropSyntax   = 'DROP DATABASE `:SCHEMA`;';
	
}