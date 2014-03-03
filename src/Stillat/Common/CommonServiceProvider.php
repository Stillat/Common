<?php namespace Stillat\Common;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Migrations\Migrator;
use Stillat\Common\Database\Tenant\TenantManager;
use Stillat\Common\Database\Tenant\DatabaseTenantRepository;
use Stillat\Common\Database\Tenant\SchemaCreator\SchemaCreatorManager as TenantSchemaCreatorManager;
use Stillat\Common\Database\Tenant\Migrations\TenantMigrator;
use Stillat\Common\Database\Console\Tenant\NameCommand as TenantNameCommand;
use Stillat\Common\Database\Console\Tenant\InstallCommand as TenantInstallCommand;
use Stillat\Common\Database\Console\Tenant\CreateCommand as TenantCreateCommand;
use Stillat\Common\Database\Console\Tenant\DropCommand as TenantDropCommand;
use Stillat\Common\Database\Console\Tenant\UninstallCommand as TenantUninstallCommand;
use Stillat\Common\Database\Console\Tenant\MigrationsCommand as TenantMigrationsCommand;
use Stillat\Common\Database\Console\Tenant\MigrateCommand as TenantMigrateCommand;
use Stillat\Common\Database\Console\Tenant\RollbackCommand as TenantRollbackCommand;
use Stillat\Common\Database\Console\Tenant\ResetCommand as TenantResetCommand;

class CommonServiceProvider extends ServiceProvider {

	public function boot()
	{
		$this->package('stillat/common');
	}

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerSchemaManager();
		$this->registerTenantRepository();
		$this->registerTenantManager();
		$this->registerTenantMigrator();
		$this->registerTenantCommands();
	}

	/**
	 * Registers the tenant manager service.
	 *
	 * @return void
	 */
	public function registerTenantManager()
	{
		$this->app['common.tenant'] = $this->app->share(function($app)
		{
			return new TenantManager($this->app, $this->app['common.tenant.schema'], $this->app['tenant.repository']);
		});
	}

	public function registerTenantMigrator()
	{
		$this->app['common.tenant.migrator'] = $this->app->share(function($app)
		{
			$repository = $app['migration.repository'];

			return new TenantMigrator($repository, $app['db'], $app['files'], $app['common.tenant']);
		});
	}

	public function registerSchemaManager()
	{
		$this->app['common.tenant.schema'] = $this->app->share(function($app)
		{
			return new TenantSchemaCreatorManager($this->app, $this->app['migration.repository']);
		});
	}

	protected function registerTenantRepository()
	{
		$this->app->bindShared('tenant.repository', function($app)
		{
			$tenantTables = $app['config']->get('tenant.tableNames', null);

			if ($tenantTables === null)
			{
				$tenantTables = array(
					'tenantTable' => 'tenant',
					'accountsTable' => 'tenant_accounts'
				);
			}

			$tenantTable = $tenantTables['tenantTable'];
			$tenantAccountTable = $tenantTables['accountsTable'];

			return new DatabaseTenantRepository($app['db'], $tenantTable, $tenantAccountTable);
		});
	}

	/**
	 * Register all of the tenant commands.
	 *
	 * @return void
	 */
	public function registerTenantCommands()
	{
		$commands = array('Install', 'Name', 'Uninstall', 'Migrations', 'Create', 'Drop', 'Migrate', 'Rollback', 'Reset');

		foreach($commands as $command)
		{
			$this->{'registerTenant'.$command.'Command'}();
		}

		$this->commands(
			'command.tenant.install',
			'command.tenant.name',
			'command.tenant.uninstall',
			'command.tenant.migrations',
			'command.tenant.create',
			'command.tenant.drop',
			'command.tenant.migrate',
			'command.tenant.rollback',
			'command.tenant.reset'
		);
	}

	protected function registerTenantInstallCommand()
	{
		$this->app->bindShared('command.tenant.install', function($app)
		{
			return new TenantInstallCommand($app['tenant.repository']);
		});
	}

	protected function registerTenantUninstallCommand()
	{
		$this->app->bindShared('command.tenant.uninstall', function($app)
		{
			return new TenantUninstallCommand($app['tenant.repository']);
		});
	}

	protected function registerTenantNameCommand()
	{
		$this->app->bindShared('command.tenant.name', function($app)
		{
			return new TenantNameCommand();
		});
	}

	protected function registerTenantCreateCommand()
	{
		$this->app->bindShared('command.tenant.create', function($app)
		{
			return new TenantCreateCommand();
		});
	}

	protected function registerTenantDropCommand()
	{
		$this->app->bindShared('command.tenant.drop', function($app)
		{
			return new TenantDropCommand();
		});
	}

	public function registerTenantMigrationsCommand()
	{
		$this->app->bindShared('command.tenant.migrations', function($app)
		{
			$packagePath = $app['path.base'].'/vendor';

			return new TenantMigrationsCommand($app['common.tenant'], $app['migrator'], $packagePath);
		});
	}

	public function registerTenantResetCommand()
	{
		$this->app->bindShared('command.tenant.reset', function($app)
		{
			return new TenantResetCommand($app['common.tenant.migrator']);
		});
	}

	public function registerTenantMigrateCommand()
	{
		$this->app->bindShared('command.tenant.migrate', function($app)
		{
			$packagePath = $app['path.base'].'/vendor';

			return new TenantMigrateCommand($app['common.tenant.migrator'], $packagePath);
		});
	}

	public function registerTenantRollbackCommand()
	{
		$this->app->bindShared('command.tenant.rollback', function($app)
		{
			$packagePath = $app['path.base'].'/vendor';

			return new TenantRollbackCommand($app['common.tenant.migrator'], $packagePath);
		});
	}


	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('common.tenant', 'common.tenant.migrator', 'common.tenant.schema', 'tenant.repository', 'command.tenant.install', 'command.tenant.name',
					 'command.tenant.uninstall', 'command.tenant.migrations', 'command.tenant.create', 'command.tenant.drop', 'command.tenant.migrate', 'command.tenant.rollback',
					 'command.tenant.reset');
	}

}