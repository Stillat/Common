<?php namespace Stillat\Common\Database\Console\Tenant;

use Illuminate\Database\Console\Migrations\BaseCommand as Command;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Database\Migrations\Migrator;
use Stillat\Common\Database\Tenant\TenantManager as Manager;
use Stillat\Common\Database\Tenant\Migrations\TenantMigrationResolver;

class MigrationsCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'tenant:migrations';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Returns the resolved tenant migrations list';

	/**
	 * The manager instance.
	 *
	 * @var \Stillat\Common\Database\Tenant\TenantManager
	 */
	protected $manager;

	/**
	 * The migrator instance.
	 *
	 * @var \Illuminate\Database\Migrations\Migrator
	 */
	protected $migrator;

	/**
	 * The path to the packages directory (vendor).
	 *
	 * @var string
	 */
	protected $packagePath;

	/**
	 * The tenant migration resolver instance.
	 *
	 * @var \Stillat\Common\Database\Tenant\Migrations\TenantMigrationResolver
	 */
	protected $tenantMigrationResolver;

	/**
	 * Create a new tenant migrations command instance.
	 *
	 * @param  \Stillat\Common\Database\Tenant\TenantManager  $manager
	 * @param  \Illuminate\Database\Migrations\Migrator  $migrator
	 * @param  string  $packagePath
	 * @return void
	 */
	public function __construct(Manager $manager, Migrator $migrator, $packagePath)
	{
		parent::__construct();

		$this->manager = $manager;

		$this->migrator = $migrator;
		$this->packagePath = $packagePath;

		// There is nothing special about the TenantMigrationResolver class, so let's just new up one.
		$this->tenantMigrationResolver = new TenantMigrationResolver;
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		if ($this->manager->getMigrationBehavior() == 'only')
		{
			foreach ($this->manager->getTenantMigrations() as $migrationName)
			{
				$this->info($migrationName);
			}
		}
		else
		{
			// This bit of code will get all of the migrations from the file system and load them into
			// an array. After the migration files are in the array, we will get the actual class name
			// from the migration file.
			$path = $this->getMigrationPath();
			$files = $this->migrator->getMigrationFiles($path);

			$migrations = array();

			foreach ($files as $file)
			{
				$migrations[] = $this->tenantMigrationResolver->resolveMigrationName($file);
			}

			$migrations = array_diff($migrations, $this->manager->getTenantMigrations());

			foreach ($migrations as $migrationName)
			{
				$this->info($migrationName);
			}
		}

	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'),
			array('path', null, InputOption::VALUE_OPTIONAL, 'The path to migration files.', null),
			array('package', null, InputOption::VALUE_OPTIONAL, 'The package to migrate.', null),
			array('bench', null, InputOption::VALUE_OPTIONAL, 'The name of the workbench to migrate.', null),
		);
	}

}