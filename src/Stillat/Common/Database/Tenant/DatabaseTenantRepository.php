<?php namespace Stillat\Common\Database\Tenant;

use Stillat\Common\Database\Repositories\BaseRepository;
use Stillat\Common\Database\Repositories\CreatableRepositoryInterface;
use Stillat\Common\Database\Tenant\TenantRepositoryInterface;
use Illuminate\Database\ConnectionResolverInterface as Resolver;
use DB;

class DatabaseTenantRepository extends BaseRepository implements CreatableRepositoryInterface, TenantRepositoryInterface {


	/**
	 * The database connection resolver instance.
	 *
	 * @var \Illuminate\Database\ConnectionResolverInterface
	 */
	protected $resolver;

	/**;
	 * The name of the database connection to use.
	 *
	 * @var string
	 */
	protected $connection;

	protected $tenantAccountTable = '';

	protected $safeDeletes = false;

	/**
	 * Create a new database tenant repository instance.
	 *
	 * @param  \Illuminate\Database\ConnectionResolverInterface  $resolver
	 * @param  string  $table
	 * @param  string  $accountTable
	 * @return void
	 */
	public function __construct(Resolver $resolver, $table, $accountTable)
	{
		$this->tableName = $table;
		$this->tenantAccountTable = $accountTable;
		$this->resolver = $resolver;
	}

	/**
	 * Create the repository data store.
	 *
	 * @return void
	 */
	public function createRepository()
	{
		// Here we are going to retrieve the name of the users table from the
		// authentication configuration that should be set in a default
		// Laravel application installation.

		$application = app();
		$usersTable = $application['config']->get('auth.table', 'users');

		$schema = $this->getConnection()->getSchemaBuilder();

		$schema->create($this->tableName, function($table)
		{
			$table->increments('id');
			$table->string('tenant_name')->default('')->unique();
			$table->boolean('active')->default(true);
			$table->timestamps();
		});

		$schema->create($this->tenantAccountTable, function($table) use ($usersTable)
		{
			$table->increments('id');
			$table->integer(str_singular($this->tableName).'_id')->unsigned();
			$table->integer(str_singular($usersTable).'_id')->unsigned();
			$table->index(str_singular($this->tableName).'_id');
			$table->index(str_singular($usersTable).'_id');
		});
	}

	public function removeRepository()
	{
		$schema = $this->getConnection()->getSchemaBuilder();

		$schema->drop($this->tableName);
		$schema->drop($this->tenantAccountTable);
	}

	public function log($tenant_id)
	{
		$this->insert(array('tenant_name' => $tenant_id, 'active' => true));
	}

	public function remove($tenant_id)
	{
		$this->table()->where('tenant_name', '=', $tenant_id)->delete();
	}

	/**
	 * Determine if the repository exists.
	 *
	 * @return bool
	 */
	public function repositoryExists()
	{
		$schema = $this->getConnection()->getSchemaBuilder();

		return $schema->hasTable($this->tableName);
	}

	/**
	 * Get the connection resolver instance.
	 *
	 * @return \Illuminate\Database\ConnectionResolverInterface
	 */
	public function getConnectionResolver()
	{
		return $this->resolver;
	}

	/**
	 * Resolve the database connection instance.
	 *
	 * @return \Illuminate\Database\Connection
	 */
	public function getConnection()
	{
		return $this->resolver->connection($this->connection);
	}

	/**
	 * Returns a tenant for the given ID.
	 *
	 * @param int $tenantID
	 * 
	 */
	public function getTenant($tenantID)
	{
		
	}

	public function grantUserOnTenant($user_id, $tenant_id)
	{
		return DB::table($this->tenantAccountTable)->insert(array('tenant_id' => $tenant_id, 'user_id' => $user_id));
	}

	public function removeUserFromTenant($user_id, $tenant_id)
	{
		return DB::table($this->tenantAccountTable)->where('tenant_id', '=', $tenant_id)->where('user_id', '=', $user_id)->delete();
	}

	public function getUserTenants($user_id)
	{
		return DB::table($this->tenantAccountTable)->where('user_id', '=', $user_id)->get();
	}

	public function getTenants()
	{
		$tenants = $this->table()->select('id', 'tenant_name', 'active')->get();

		return $tenants;
	}

	public function setSource($name)
	{
		$this->connection = $name;
	}

}