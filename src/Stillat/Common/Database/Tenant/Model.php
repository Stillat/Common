<?php namespace Stillat\Common\Database\Tenant;

use Stillat\Common\Database\Model as StillatModel;
use Stillat\Common\Database\Tenant\TenantManager as Manager;

abstract class Model extends StillatModel {

	/**
	 * Returns a new instance of the tenant model.
	 *
	 * @return Stillat\Common\Database\Tenant\Model
	 */
	public function __construct(array $attributes = array())
	{
		parent::__construct($attributes);

		$manager = Manager::instance();
		$this->setConnection($manager->getCurrentConnection());
		unset($manager);
	}

}