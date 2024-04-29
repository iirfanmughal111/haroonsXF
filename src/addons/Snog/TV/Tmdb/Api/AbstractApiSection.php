<?php

namespace Snog\TV\Tmdb\Api;


abstract class AbstractApiSection
{
	/**
	 * @var \Snog\TV\Tmdb\ApiClient
	 */
	protected $client;

	public function __construct(\Snog\TV\Tmdb\ApiClient $client)
	{
		$this->client = $client;
	}
}
