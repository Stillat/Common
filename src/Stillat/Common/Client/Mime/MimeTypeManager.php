<?php namespace Stillat\Common\Client\Mime;

use Stillat\Common\Client\Mime\MimeResolverInterface;

class MimeTypeManager {

	/**
	 * The MIME resolver implementation.
	 *
	 * @var Stillat\Common\Client\Mime\MimeResolverInterface
	 */
	protected $mimeResolverInstance;

	/**
	 * Returns a new mime type manager instance.
	 *
	 * @param Stillat\Common\Client\Mime\MimeResolverInterface $mimeResolverInstance
	 */
	public function __construct(MimeResolverInterface $mimeResolverInstance)
	{
		$this->mimeResolverInstance = $mimeResolverInstance;
	}

	/**
	 * Resolves a given extension to a MIME Type.
	 *
	 * @return Stillat\Common\Client\Mime\MimeType
	 */
	public function resolve($extension)
	{
		return $this->mimeResolverInstance->resolveMime($extension);
	}

	public function test()
	{
		return 'hi';
	}

}