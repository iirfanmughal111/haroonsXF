<?php

namespace Snog\Movies\Tmdb\Api;

class Movies extends AbstractApiSection
{
	protected $movieId;

	public function __construct(\Snog\Movies\Tmdb\ApiClient $client, $movieId)
	{
		parent::__construct($client);

		$this->movieId = $movieId;
	}

	public function getDetails($subRequests = [])
	{
		$params = [];
		if ($subRequests !== null)
		{
			$params = ['append_to_response' => implode(',', $subRequests)];
		}

		return $this->client->get("movie/$this->movieId", $params)->toArray();
	}

	public function getCredits()
	{
		return $this->client->get("movie/$this->movieId/credits")->toArray();
	}

	public function getVideos()
	{
		return $this->client->get( "movie/$this->movieId/videos")->toArray();
	}

	public function getWatchProviders()
	{
		return $this->client->get("movie/$this->movieId/watch/providers")->toArray();
	}

	public function getChanges(array $subRequests = [])
	{
		return $this->client->get( "movie/$this->movieId/changes", $subRequests)->toArray();
	}
}