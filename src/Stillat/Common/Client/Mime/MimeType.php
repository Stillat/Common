<?php namespace Stillat\Common\Client\Mime;

use Stillat\Common\InvalidArgumentException;

class MimeType {

	/**
	 * The MIME Type.
	 *	 
	 * @var string
	 */
	protected $type;

	/**
	 * The PHP headers associated with the given type.
	 *	 
	 * @var array
	 */
	protected $headers = array();

	/**
	 * Returns a new instance of a mime type.
	 *
	 * @param  string $mimeType
	 * @param  array  $headers
	 * @return Stillat\Common\Client\Mime\MimeType
	 *
	 * @throws Stillat\Common\InvalidArgumentException
	 */
	public function __construct($mimeType, array $headers)
	{
		if (is_string($mimeType))
		{
			$this->type = $mimeType
		}
		else
		{
			throw new InvalidArgumentException('A given MIME Type must be represented as a string.');
		}

		$this->headers = $headers;
	}

	/**
	 * Returns the PHP headers for the MIME type.
	 *
	 * @return array
	 */
	public function getHeaders()
	{
		return $this->headers;
	}

	/**
	 * Returns the MIME type.
	 *	 
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Returns the MIME type.
	 *	 
	 * @return string
	 */
	public function __toString()
	{
		return $this->getType();
	}

}