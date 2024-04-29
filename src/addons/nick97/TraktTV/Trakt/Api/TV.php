<?php

namespace nick97\TraktTV\Trakt\Api;


class TV extends AbstractApiSection
{
	protected $showId;

	public function __construct(\nick97\TraktTV\Trakt\ApiClient $client, $showId)
	{
		parent::__construct($client);

		$this->showId = $showId;
	}

	public function getDetails($subRequests = [])
	{
		$params = [];
		if ($subRequests !== null) {
			$params = ['append_to_response' => implode(',', $subRequests)];
		}

		return $this->client->get("tv/$this->showId", $params)->toArray();
	}

	public function getCredits()
	{
		return $this->client->get("tv/$this->showId/credits")->toArray();
	}

	public function getAggregateCredits()
	{
		return $this->client->get("tv/$this->showId/aggregate_credits")->toArray();
	}

	public function getExternalIds()
	{
		return $this->client->get("tv/$this->showId/external_ids")->toArray();
	}

	public function getVideos()
	{
		return $this->client->get("tv/$this->showId/videos")->toArray();
	}

	public function getWatchProviders()
	{
		return $this->client->get("tv/$this->showId/watch/providers")->toArray();
	}

	public function getChanges($params = [])
	{
		return $this->client->get("tv/$this->showId/changes", $params)->toArray();
	}

	// ################################## SEASONS ###########################################

	public function getSeason($season)
	{
		return new \nick97\TraktTV\Trakt\Api\TV\Seasons($this->client, $this->showId, $season);
	}
}
