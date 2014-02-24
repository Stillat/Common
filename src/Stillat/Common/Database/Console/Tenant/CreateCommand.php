<?php namespace Stillat\Common\Database\Console\Tenant;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Stillat\Common\Database\Tenant\TenantManager;
use Symfony\Component\Console\Input\InputArgument;
use Exception;

class CreateCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'tenant:create';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Creates the new tenant with a given name';

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$tenantManager = $this->laravel['common.tenant'];
		
		try
		{
			$tenantManager->createTenant($this->argument('name'));
			$this->info($tenantManager->getTierNameWithPrefix($this->argument('name')).' created successfully');
		} catch (Exception $e)
		{
			throw $e;
		}
	}

	protected function getArguments()
	{
		return array(
			array('name', InputArgument::REQUIRED, 'The account ID in question')
		);
	}

}