<?php namespace Stillat\Common\Client;

use Illuminate\Support\ServiceProvider;
use Stillat\Common\Client\DownloadManager;
use Stillat\Common\Client\Mime\MimeTypeManager;
use Stillat\Common\Client\ClientManager;

class ClientServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerDependencies();
		$this->registerClientManager();
		$this->registerMimeResolver();
		$this->registerDownloader();
	}

	/**
	 * Registers some IoC dependencies.
	 *
	 * @return void
	 */
	protected function registerDependencies()
	{
		$this->app->singleton('Stillat\Common\Client\DownloaderInterface', function()
		{
			return new LargeFileDownloader;
		});

		$this->app->singleton('Stillat\Common\Client\Mime\MimeResolverInterface', function()
		{
			return new Mime\DatabaseMimeResolver;
		});
	}

	/**
	 * Registers the download service.
	 *
	 * @return void
	 */
	protected function registerDownloader()
	{
		$this->app->bindShared('downloader', function($app)
		{
			return new DownloadManager($this->app->make('Stillat\Common\Client\DownloaderInterface'));
		});
	}

	/**
	 * Registers the mime resolver service.
	 *
	 * @return void
	 */
	protected function registerMimeResolver()
	{
		$this->app->bindShared('mime', function($app)
		{
			return new MimeTypeManager($this->app->make('Stillat\Common\Client\Mime\MimeResolverInterface'));
		});
	}

	/**
	 * Registers the client manager service.
	 *
	 * @return void
	 */
	protected function registerClientManager()
	{
		$this->app->bindShared('client.manager', function($app)
		{
			return new ClientManager;
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array(
			'downloader', 'mime.resolver', 'client.manager',
		);
	}

}