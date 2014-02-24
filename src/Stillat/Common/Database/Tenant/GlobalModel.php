<?php namespace Stillat\Common\Database\Tenant;

use Stillat\Common\Database\Model as StillatModel;
use Stillat\Common\Database\Tenant\TenantManager as Manager;

abstract class GlobalModel extends StillatModel {

	/**
	 * Returns a new instance of the global tenant model.
	 *
	 * @return Stillat\Common\Database\Tenant\GlobalModel
	 */
	public function __construct(array $attributes = array())
	{
		parent::__construct($attributes);

	}

	public function save(array $options = array())
	{


		$manager = Manager::instance();
		$tenants = $manager->getRepository()->getTenants();

		$tenantSuccess = array();

		foreach ($tenants as $tenant)
		{
			$manager->bootstrapConnectionByTenantName($tenant->tenant_name);
			
			$temporaryModel = clone $this;
			$temporaryModel->setConnection($tenant->tenant_name);
			$temporaryModel->parentSave($options);
			unset($temporaryModel);

		}

		
	}

	public function parentSave(array $options = array())
	{
		parent::save($options);
	}

}