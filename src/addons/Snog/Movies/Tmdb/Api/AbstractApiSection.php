<?php

namespace Snog\Movies\Tmdb\Api;


abstract class AbstractApiSection
{
	/**
	 * @var \Snog\Movies\Tmdb\ApiClient
	 */
	protected $client;

	public function __construct(\Snog\Movies\Tmdb\ApiClient $client)
	{
		$this->client = $client;
	}
}
