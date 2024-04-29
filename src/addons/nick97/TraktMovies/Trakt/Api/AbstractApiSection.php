<?php

namespace nick97\TraktMovies\Trakt\Api;


abstract class AbstractApiSection
{
	/**
	 * @var \nick97\TraktMovies\Trakt\ApiClient
	 */
	protected $client;

	public function __construct(\nick97\TraktMovies\Trakt\ApiClient $client)
	{
		$this->client = $client;
	}
}
