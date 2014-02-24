<?php namespace Stillat\Common\Database\Tenant\SchemaCreator;

use Illuminate\Foundation\Application;
use Stillat\Common\Database\Tenant\TenantManager;
use Stillat\Common\Database\Tenant\TenantException;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;
use Stillat\Common\Database\Tenant\SchemaCreator\Drivers\MySqlDriver;
use Stillat\Common\Database\Tenant\SchemaCreator\Drivers\PostgresDriver;
use Stillat\Common\Database\Tenant\SchemaCreator\Drivers\SqlServerDriver;

class SchemaCreatorManager {


	/**
	 * The application instnace.
	 *
	 * @var \Illuminate\Foundation\Application
	 */
	protected $app = null;

	/**
	 * The SchemaCreatorInterface implementation.
	 *
	 * @var \Stillat\Common\Database\Tenant\SchemaCreator\SchemaCreatorInterface
	 */
	protected $schemaCreatorDriver;

	/**
	 * The schema creator implementation.
	 *
	 * @var \Stillat\Common\Database\Tenant\SchemaCreator\SchemaCreatorInterface
	 */
	protected $schemaDriver;

	/**
	 * The repository instance.
	 *
	 * @var \Illuminate\Database\Migrations\MigrationRepositoryInterface
	 */
	protected $repository;

	/**
	 * Returns a new SchemaCreatorManager instance.
	 *
	 *
	 * @param  \Illuminate\Database\Migrations\MigrationRepositoryInterface  $repository
	 */
	public function __construct(Application $app, MigrationRepositoryInterface $repository)
	{
		$this->app = $app;

		$this->repository = $repository;

		// Here we are going to resolve the schema driver. We will do this by getting the driver
		// for the default database connection. As far as the tenant service is concerned, the default
		// database connection will act as the hub.
		$dataConnections = $this->app['config']->get(TenantManager::CONFIGURATION_KEY_NAME_PREFIX);
		$defaultConnection = $dataConnections[$this->app['config']->get(TenantManager::CONFIGURATION_DEFAULT_CONNECTION_NAME)];

		$defaultConnectionDriver = $defaultConnection['driver'];

		$hostName = $defaultConnection['host'];
		$username = $defaultConnection['username'];
		$password = $defaultConnection['password'];

		switch ($defaultConnectionDriver)
		{
			case 'mysql':
				$this->schemaDriver = new MySqlDriver($hostName, $username, $password);
				break;
			case 'pgsql':
				$this->schemaDriver = new PostgresDriver($hostName, $username, $password);
				break;
			case 'sqlsrv':
				$this->schemaDriver = new SqlServerDriver($hostName, $username, $password);
				break;
			default:
				throw new TenantException("Driver '{$defaultConnectionDriver}' is not a valid schema creator driver.");
				break;
		}



	}

	public function getDriver()
	{
		return $this->schemaDriver;
	}

	public function getRepository()
	{
		return $this->repository;
	}

	public function createSchema($schemaName)
	{
		$schema = $this->schemaDriver->createSchema($schemaName);
		$this->repository->setSource($schemaName);
		$this->repository->createRepository();
		return $schema;
	}


	public function dropSchema($schemaName)
	{
		return $this->schemaDriver->dropSchema($schemaName);
	}

}