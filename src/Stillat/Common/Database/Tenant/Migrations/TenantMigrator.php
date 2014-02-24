<?php namespace Stillat\Common\Database\Tenant\Migrations;


use Illuminate\Database\Migrations\Migrator;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;
use Illuminate\Database\ConnectionResolverInterface as Resolver;
use Illuminate\Filesystem\Filesystem;
use Stillat\Common\Database\Tenant\TenantManager;

class TenantMigrator extends Migrator {

	/**
	 * The TenantManager instance.
	 *
	 * @var \Stillat\Common\Database\Tenant\TenantManager
	 */
	protected $manager;

	/**
	 * The tenant migration resolver instance.
	 *
	 * @var \Stillat\Common\Database\Tenant\Migrations\TenantMigrationResolver
	 */
	protected $tenantMigrationResolver;

	/**
	 * Create a new migrator instance.
	 *
	 * @param  \Illuminate\Database\Migrations\MigrationRepositoryInterface  $repository
	 * @param  \Illuminate\Database\ConnectionResolverInterface  $resolver
	 * @param  \Illuminate\Filesystem\Filesystem  $files
	 * @param  \Stillat\Common\Database\Tenant\TenantManager $manager
	 * @return void
	 */
	public function __construct(MigrationRepositoryInterface $repository,
								Resolver $resolver,
                                Filesystem $files,
                                TenantManager $manager)
	{
		$this->manager = $manager;

		// There is nothing special about the TenantMigrationResolver class, so let's just new up one.
		$this->tenantMigrationResolver = new TenantMigrationResolver;

		parent::__construct($repository, $resolver, $files);
	}


	/**
	 * Run the outstanding migrations at a given path.
	 *
	 * @param  string  $path
	 * @param  bool    $pretend
	 * @return void
	 */
	public function run($path, $pretend = false)
	{
		$tenants = $this->manager->getRepository()->getTenants();

		$migrationsFileList = array();

		$configuredMigrations = $this->manager->getTenantMigrations();

		$availableMigrations = $this->getMigrationFiles($path);

		foreach ($availableMigrations as $migration)
		{
			$migrationName = $this->tenantMigrationResolver->resolveMigrationName($migration);

			if ($this->manager->getMigrationBehavior() == 'only')
			{
				if (in_array($migrationName, $configuredMigrations))
				{
					$migrationsFileList[] = $migration;
				}
			}
			else
			{
				if (in_array($migrationName, $configuredMigrations) == false)
				{
					$migrationsFileList[] = $migration;
				}
			}
		}

		$this->requireFiles($path, $migrationsFileList);


		$this->note('Assembling tenant migrations list...');
		$this->note('');

		foreach ($migrationsFileList as $migration)
		{
			$this->note('<info>Queued:</info> '.$migration);
		}


		$this->note('');

		// Go through each tenant and then create and then bootstrap
		// their connections.
		foreach ($tenants as $tenant)
		{
			$this->manager->bootstrapConnectionByTenantName($tenant->tenant_name);
			$this->setConnection($tenant->tenant_name);
			$this->repository->setSource($tenant->tenant_name);
			$this->note('<info>Bootstrapped connection for:</info> '.$tenant->tenant_name);

			// Once we grab all of the migration files for the path, we will compare them
			// against the migrations that have already been run for this package then
			// run all of the oustanding migrations against the database connection.
			$ran = $this->repository->getRan();

			$migrations = array_diff($migrationsFileList, $ran);


			$this->note('<info>Running migrations on:</info> '.$tenant->tenant_name);
			$this->runMigrationList($migrations, $pretend);

		}
	}

	/**
	 * Rollback the last migration operation.
	 *
	 * @param  bool  $pretend
	 * @return int
	 */
	public function rollback($pretend = false)
	{
		$this->notes = array();


		$tenants = $this->manager->getRepository()->getTenants();

		$totalMigrations = 0;

		foreach ($tenants as $tenant)
		{
			$this->manager->bootstrapConnectionByTenantName($tenant->tenant_name);
			$this->setConnection($tenant->tenant_name);
			$this->repository->setSource($tenant->tenant_name);
			$this->note('<info>Bootstrapped connection for:</info> '.$tenant->tenant_name);

			// We want to pull in the last batch of migrations that ran on the previous
			// migration operation. We'll then reverse those migrations and run each
			// of them "down" to reverse the last migration "operation" which ran.
			$migrations = $this->repository->getLast();

			if (count($migrations) == 0)
			{
				$this->note('<info>Nothing to rollback.</info>');

				return count($migrations);
			}

			// We need to reverse these migrations so that they are "downed" in reverse
			// to what they run on "up". It lets us backtrack through the migrations
			// and properly reverse the entire database schema operation that ran.
			foreach ($migrations as $migration)
			{
				$this->runDown((object) $migration, $pretend);
			}

			$totalMigrations = count($migrations);

		}

		return $totalMigrations;
	}

}