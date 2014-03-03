<?php namespace Stillat\Common\Database\Tenant;

use Illuminate\Foundation\Application;
use Stillat\Common\Database\Tenant\TenantException;
use Stillat\Common\Database\Tenant\SchemaCreator\SchemaCreatorManager;

class TenantManager {

	/**
	 * The name of the tenancy session directive.
	 *
	 * @var string
	 */
	const TENANT_SESSION_DIRECTIVE_NAME = 'tenancy.connectionName';

	/**
	 * The separator to use for separating schema names from prefixes.
	 *
	 * @var string
	 */
	const TENANT_SCHEMA_SEPARATOR = '_';

	/**
	 * The name of the Laravel configuration directive for database connections.
	 *
	 * @var string
	 */
	const CONFIGURATION_KEY_NAME_PREFIX = 'database.connections';

	/**
	 * The name of the Laravel configuration directive for default database connections.
	 *
	 * @var string
	 */
	const CONFIGURATION_DEFAULT_CONNECTION_NAME = 'database.default';

	/**
	 * The fully qualified name of the Laravel migration base class.
	 *
	 * @var string
	 */
	const LARAVEL_MIGRATION_BASE_CLASS = 'Illuminate\Database\Migrations\Migration';

	/**
	 * The application instnace.
	 *
	 * @var \Illuminate\Foundation\Application
	 */
	protected $app = null;

	/**
	 * The migrations used by the tenants.
	 *
	 * @var array
	 */
	protected $tenantMigrations = array();

	/**
	 * The schema prefix that should be used, if any.
	 *
	 * @var string
	 */
	protected $schemaPrefix = '';

	/**
	 * Indicates whether the manager will preserve database configuration
	 * settings for read/write servers.
	 *
	 * @var bool
	 */
	protected $preserveReadWriteConfiguration = false;

	/**
	 * The collection of connections the tenant manager is hosting.
	 *
	 * @var array
	 */
	protected $tenantConnections = array();

	/**
	 * The SchemaCreatorManager instance.
	 *
	 * @var \Stillat\Common\Database\Tenant\SchemaCreator\SchemaCreatorManager
	 */
	protected $schemaManager = null;

	/**
	 * The TenantRepository implementation.
	 *
	 * @var \Stillat\Common\Database\Tenant\TenantRepositoryInterface
	 */
	protected $tenantRepository = null;

	/**
	 * The behavior of the migration behavior.
	 *
	 * @var string
	 */
	protected $migrationBehavior = 'exclude';

	/**
	 * The accepted accepted migration behaviors.
	 *
	 * @var array
	 */
	public static $acceptedMigrationBehaviors = array('only', 'exclude');

	/**
	 * Creates a new instance of the tenant manager.
	 *
	 * @param  \Illuminate\Foundation\Application $app
	 * @param  \Stillat\Common\Database\Tenant\SchemaCreator\SchemaCreatorManager $manager
	 * @param  \Stillat\Common\Database\Tenant\TenantRepositoryInterface $repository
	 * @return \Stillat\Common\Database\Tenant\TenantManager
	 */
	public function __construct(Application $app, SchemaCreatorManager $manager, TenantRepositoryInterface $repository)
	{
		$this->app = $app;

		$this->schemaManager = $manager;

		$this->tenantRepository = $repository;

		$this->schemaPrefix = $this->app['config']->get('tenant.schemaPrefix', '');

		$this->preserveReadWriteConfiguration = $this->app['config']->get('tenant.preserveReadWrite', false);

		// Get the migration behavior from the tenant configuration file.
		$this->migrationBehavior = $this->app['config']->get('tenant.migrationBehavior', 'exclude');

		// Just convert the migration behavior to lower-case.
		$this->migrationBehavior = strtolower($this->migrationBehavior);

		if (in_array($this->migrationBehavior, self::$acceptedMigrationBehaviors) == false)
		{
			// If the provided migration behavior is not in the accepted behavior list, we are just going
			// to reset it to 'exclude'.
			$this->migrationBehavior = 'exclude';
		}

		// Check to see if there are any migrations listed in the tenant configuration file. If there are,
		// try and add them.
		$tenantMigrationCollection = $this->app['config']->get('tenant.migrations', null);

		if ($tenantMigrationCollection !== null and is_array($tenantMigrationCollection) and count($tenantMigrationCollection) > 0)
		{
			foreach ($tenantMigrationCollection as $migration)
			{
				$this->addMigration($migration);
			}
		}

	}

	public function bootstrapConnectionByTenantName($tenantName)
	{
		if (in_array($tenantName, $this->tenantConnections) == false)
		{
			$connectionKey = self::CONFIGURATION_KEY_NAME_PREFIX.'.'.$tenantName;

			$configuration = $this->app['config'];

			$dataConnections = $configuration->get(self::CONFIGURATION_KEY_NAME_PREFIX);
			$defaultConnection = $dataConnections[$configuration->get(self::CONFIGURATION_DEFAULT_CONNECTION_NAME)];

			$tenantConnectionSettings = array();

			if ($this->preserveReadWriteConfiguration)
			{
				// If the user knows what they are doing and really wants to preserve the
				// read/write server settings, just let them.
				$tenantConnectionSettings = $defaultConnection;
			}
			else
			{
				// Remove the read and write configuration settings if they exist. It doesn't make sense
				// to use the default connections read/write settings if the user will be connecting to
				// their own database and connection.
				$tenantConnectionSettings = array_except($defaultConnection, array('read', 'write'));
			}

			// This simply overrides the database name.
			$tenantConnectionSettings['database'] = $tenantName;

			// This will build the new database connection for the request.
			$this->app['config']->set($connectionKey, $tenantConnectionSettings);
			$this->tenantConnections[] = $tenantName;
		}

		return $tenantName;
	}

	/**
	 * Causes the tenant manager to register a new database connection for
	 * a given account ID.
	 *
	 * @param  int  $accountID
	 * @return string
	 */
	protected function bootstrapConnection($accountID)
	{
		$tenantName = $this->getTierNameWithPrefix($accountID);

		if (in_array($tenantName, $this->tenantConnections) == false)
		{
			$connectionKey = self::CONFIGURATION_KEY_NAME_PREFIX.'.'.$tenantName;

			$configuration = $this->app['config'];

			$dataConnections = $configuration->get(self::CONFIGURATION_KEY_NAME_PREFIX);
			$defaultConnection = $dataConnections[$configuration->get(self::CONFIGURATION_DEFAULT_CONNECTION_NAME)];

			$tenantConnectionSettings = array();

			if ($this->preserveReadWriteConfiguration)
			{
				// If the user knows what they are doing and really wants to preserve the
				// read/write server settings, just let them.
				$tenantConnectionSettings = $defaultConnection;
			}
			else
			{
				// Remove the read and write configuration settings if they exist. It doesn't make sense
				// to use the default connections read/write settings if the user will be connecting to
				// their own database and connection.
				$tenantConnectionSettings = array_except($defaultConnection, array('read', 'write'));
			}

			// This simply overrides the database name.
			$tenantConnectionSettings['database'] = $tenantName;

			// This will build the new database connection for the request.
			$this->app['config']->set($connectionKey, $tenantConnectionSettings);

			$this->tenantConnections[] = $tenantName;
		}

		return $tenantName;
	}

	/**
	 * Causes the tenant manager to switch the current connection
	 * to the account with the given account ID.
	 *
	 * @param int $accountID
	 */
	public function assumeTenant($accountID)
	{
		$this->app['session']->put(self::TENANT_SESSION_DIRECTIVE_NAME, $this->bootstrapConnection($accountID));
	}

	/**
	 * Returns the current connection name.
	 *
	 * @return mixed
	 */
	public function getCurrentConnection()
	{
		$accountName = $this->app['session']->get(self::TENANT_SESSION_DIRECTIVE_NAME, null);

		if ($accountName == null)
		{
			return null;
		}
		else
		{
			return $this->schemaPrefix.$accountName;
		}
	}

	/**
	 * Returns the name of a tenant tier.
	 *
	 * @param  int $tierID
	 * @return string
	 */
	public function getTierName($tierID)
	{
		$tierID = intval($tierID);

		// All this does is perform some string replacement functions
		// to convert a numeric-based $tierID to a string representation.
		// This is all arbitrary, it just has to be consistent.

		$tierName = md5($tierID);
		$tierName = str_replace('1', 'a', $tierName);
		$tierName = str_replace('2', 'c', $tierName);
		$tierName = str_replace('3', 'n', $tierName);
		$tierName = str_replace('4', 'q', $tierName);
		$tierName = str_replace('5', 'i', $tierName);
		$tierName = str_replace('6', 'm', $tierName);
		$tierName = str_replace('7', 'k', $tierName);
		$tierName = str_replace('8', 'e', $tierName);
		$tierName = str_replace('9', 'o', $tierName);
		$tierName = str_replace('0', 'z', $tierName);
		$tierName = strtolower($tierName);

		return $tierName;
	}

	/**
	 * Returns the name of a tenant tier withs its schema prefix, if any.
	 *
	 * @param  int $tierID
	 * @return string
	 */
	public function getTierNameWithPrefix($tierID)
	{
		if (strlen($this->getSchemaPrefix()) == 2)
		{
			return $this->getSchemaPrefix().self::TENANT_SCHEMA_SEPARATOR.$this->getTierName($tierID);
		}

		return $this->getTierName($tierID);
	}

	/**
	 * Returns the configured schema prefix.
	 *
	 * @return string
	 */
	public function getSchemaPrefix()
	{
		return $this->schemaPrefix;
	}

	/**
	 * Adds a migration to the manager's migration list.
	 *
	 * @param string $migrationName
	 * @throws TenantException
	 */
	public function addMigration($migrationName)
	{
		if (class_exists($migrationName))
		{

			//$this->tenantMigrations[] = $migrationName;

			if (is_subclass_of($migrationName, self::LARAVEL_MIGRATION_BASE_CLASS))
			{
				$this->tenantMigrations[] = $migrationName;
			}
			else
			{
				throw new TenantException("The class '{$migrationName}' does not extend '".self::LARAVEL_MIGRATION_BASE_CLASS."'");
			}
		}
		else
		{
			throw new TenantException("The migration '{$migrationName}' does not exist, or cannot be found.");
		}		
	}

	/**
	 * Removes a migration from the manager's migration list.
	 *
	 * @param string $migrationName
	 */
	public function removeMigration($migrationName)
	{
		unset($this->tenantMigrations[$migrationName]);
	}

	/**
	 * Returns the migrations associated with the tenant system.
	 *
	 * @return array
	 */
	public function getTenantMigrations()
	{
		return $this->tenantMigrations;
	}

	/**
	 * Returns the migration behavior.
	 *
	 * @return string
	 */
	public function getMigrationBehavior()
	{
		return $this->migrationBehavior;
	}

	/**
	 * Returns the internal SchemaCreatorManager instance.
	 *
	 * @return \Stillat\Common\Database\Tenant\SchemaCreator\SchemaCreatorManager
	 */
	public function getSchemaManager()
	{
		return $this->schemaManager;
	}

	public function getRepository()
	{
		return $this->tenantRepository;
	}

	public function createTenant($tenantID)
	{
		$this->bootstrapConnectionByTenantName($this->getTierNameWithPrefix($tenantID));
		$this->schemaManager->createSchema($this->getTierNameWithPrefix($tenantID));
		$this->tenantRepository->log($this->getTierNameWithPrefix($tenantID));

		// The next thing we have to do is install a migrations table for the new tenant.
	}

	public function dropTenant($tenantID)
	{
		$this->schemaManager->dropSchema($this->getTierNameWithPrefix($tenantID));
		$this->tenantRepository->remove($this->getTierNameWithPrefix($tenantID));
	}

	/**
	 * Returns an instance of the Tenant Manager
	 *
	 * @return \Stillat\Common\Database\Tenant\TenantManager
	 */
	public static function instance()
	{
		$application = app();
		return $application->make('common.tenant');
	}
	
}