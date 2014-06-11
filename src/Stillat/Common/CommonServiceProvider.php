<?php namespace Stillat\Common;

use Illuminate\Support\ServiceProvider;
use Stillat\Common\Math\MathManager;
use Stillat\Common\Collections\Sorting\SortingManager;

class CommonServiceProvider extends ServiceProvider {

	public function boot()
	{
		$this->package('stillat/common', 'stillat-common');
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
		$this->registerSortManager();
		$this->registerMathManager();
	}

	private function registerSortManager()
	{
		$this->app->bind('stillat-common.sortmanager', function($app)
		{

			$sortingDriver = $app['config']->get('stillat-common::sorting.driver');

			$sortingDrivers = $app['config']->get('stillat-common::sorting.sortingDrivers');

			return new SortingManager($sortingDriver, $sortingDrivers);
		});
	}

	private function registerMathManager()
	{
		$this->app->bind('stillat-common.mathmanager', function($app)
		{

			$expressionEngine = $app['config']->get('stillat-common::math.driver');

			$precision        = $app['config']->get('stillat-common::math.precision');

			return with(new MathManager($expressionEngine))->setPrecision($precision);

		});
	}
	
	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('stillat-common.sortmanager', 'stillat-common.mathmanager');
	}

}