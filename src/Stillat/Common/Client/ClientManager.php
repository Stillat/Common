<?php namespace Stillat\Common\Client;

use Illuminate\Http\Request;

class ClientManager {

	/**
	 * The request instance.
	 *
	 */
	private $requestInstance;

	/**
	 * Returns an instance of the client manager.
	 *
	 * @return Stillat\Common\Client
	 */
	public function __construct(Request $request)
	{
		$this->requestInstance = $request;
	}

}