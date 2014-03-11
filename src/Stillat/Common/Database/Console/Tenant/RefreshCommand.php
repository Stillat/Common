<?php namespace Stillat\Common\Database\Console\Tenant;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Stillat\Common\Database\Tenant\Migrations\TenantMigrator;
use Stillat\Common\Database\Tenant\TenantManager as Manager;
use Stillat\Common\Database\Tenant\Migrations\TenantMigrationResolver;

class RefreshCommand extends Command {
	
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'tenant:refresh';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Reset and re-run all tenant migrations';

	/**
	 * The migrator instance.
	 *
	 * @var \Stillat\Common\Database\Tenant\TenantMigrator
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
	 * Create a new tenant migrations refresh instance.
	 *
	 * @param  \Stillat\Common\Database\Tenant\Migrations\TenantMigrator  $migrator
	 * @param  string  $packagePath
	 * @return \Stillat\Common\Database\Tenant\Migrations\RefreshCommand
	 */
	public function __construct(TenantMigrator $migrator, $packagePath)
	{
		parent::__construct();

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

	}

}