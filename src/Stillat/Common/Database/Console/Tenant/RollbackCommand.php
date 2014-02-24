<?php namespace Stillat\Common\Database\Console\Tenant;

use Illuminate\Database\Console\Migrations\BaseCommand as Command;
use Symfony\Component\Console\Input\InputOption;
use Stillat\Common\Database\Tenant\Migrations\TenantMigrator;
use Stillat\Common\Database\Tenant\TenantManager as Manager;
use Stillat\Common\Database\Tenant\Migrations\TenantMigrationResolver;

class RollbackCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'tenant:rollback';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Rollback the last database migration for all tenants';

	/**
	 * The migrator instance.
	 *
	 * @var \Stillat\Common\Database\Tenant\TenantMigrator
	 */
	protected $migrator;

	/**
	 * Create a new migration rollback command instance.
	 *
	 * @param  \Illuminate\Database\Migrations\Migrator  $migrator
	 * @return void
	 */
	public function __construct(TenantMigrator $migrator)
	{
		parent::__construct();

		$this->migrator = $migrator;
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$pretend = $this->input->getOption('pretend');

		$this->migrator->rollback($pretend);

		// Once the migrator has run we will grab the note output and send it out to
		// the console screen, since the migrator itself functions without having
		// any instances of the OutputInterface contract passed into the class.
		foreach ($this->migrator->getNotes() as $note)
		{
			$this->output->writeln($note);
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

			array('pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run.'),
		);
	}

}