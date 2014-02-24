<?php namespace Stillat\Common\Database\Tenant\SchemaCreator\Drivers;

use Stillat\Common\Database\Tenant\SchemaCreator\SchemaCreatorInterface;
use Stillat\Common\Database\Tenant\TenantException;
use PDO, Exception;

class PDOSchemaDriver implements SchemaCreatorInterface {


	protected $pdoDriver = '';

	protected $createSyntax = '';

	protected $dropSyntax = '';

	protected $host = '';

	protected $username = '';

	protected $password = '';

	public function __construct($host, $username, $password)
	{
		$this->host = $host;
		$this->username = $username;
		$this->password = $password;
	}

	protected function getConnection()
	{
		return $this->pdoDriver;
	}

	protected function buildConnection($host, $username, $password)
	{
		try
		{
			$connection = new PDO($this->getConnection().':host='.$host, $username, $password);
			$connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
			return $connection;
		} catch (Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * Creates a new database schema.
	 *
	 * @param  string $schemaName
	 * @param  string $host
	 * @param  string $username
	 * @param  string $password
	 * @return bool
	 */
	public function createSchema($schemaName)
	{
		$this->validateTenantSchemaName($schemaName);
		
		try
		{
			$connection = $this->buildConnection($this->host, $this->username, $this->password);
			$query = $connection->prepare($this->convertSyntax($this->createSyntax, $schemaName));
			$query->execute(array($schemaName));

			return true;
		} catch (Exception $e)
		{
			throw $e;
		}
	}

	public function dropSchema($schemaName)
	{
		$this->validateTenantSchemaName($schemaName);
		
		try
		{
			$connection = $this->buildConnection($this->host, $this->username, $this->password);
			$query = $connection->prepare($this->convertSyntax($this->dropSyntax, $schemaName));
			$query->execute(array($schemaName));

			return true;
		} catch (Exception $e)
		{
			throw $e;
		}
	}

	protected function convertSyntax($sql, $schema)
	{
		return str_replace(':SCHEMA', $schema, $sql);
	}

	protected function validateTenantSchemaName($tenant)
	{
		if (strlen(trim($tenant)) > 35)
		{
			throw new TenantException("Invalid tenant name '{$tenant}'. Tenant name too long.");
		}

		if (str_contains($tenant, '_'))
		{
			$tenantParts = explode('_', $tenant);

			$schemaPrefix = $tenantParts[0];
			$schemaName   = $tenantParts[1];

			if (strlen(trim($schemaPrefix)) !== 2)
			{
				throw new TenantException("Invalid tenant prefix '{$schemaPrefix}'. Schema prefix too long.");
			}

			if (strlen(trim($schemaName)) !== 32)
			{
				throw new TenantException("Invalid tenant suffix '{$schemaName}'. Schema Suffix too long.");
			}
		}
	}

}