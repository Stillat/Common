<?php namespace Stillat\Common\Database\Tenant\Repositores;


use Stillat\Common\Database\Tenant\TenantManager as Manager;
use Stillat\Common\Database\Repositories\ConnectionRepository;

abstract class BaseRepository extends ConnectionRepository {

	public function __construct()
	{
		parent::__construct();

		$manager = Manager::instance();
		$this->setConnection($manager->getCurrentConnection());
		unset($manager);
	}

}