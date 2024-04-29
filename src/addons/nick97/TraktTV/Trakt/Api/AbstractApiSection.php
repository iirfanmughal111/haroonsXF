<?php

namespace nick97\TraktTV\Trakt\Api;


abstract class AbstractApiSection
{
	/**
	 * @var \nick97\TraktTV\Trakt\ApiClient
	 */
	protected $client;

	public function __construct(\nick97\TraktTV\Trakt\ApiClient $client)
	{
		$this->client = $client;
	}
}
