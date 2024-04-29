<?php

namespace nick97\TraktTV\Trakt\Api\TV;

use nick97\TraktTV\Trakt\Api\AbstractApiSection;

class Episodes extends AbstractApiSection
{
	protected $showId;
	protected $season;
	protected $episode;

	public function __construct(\nick97\TraktTV\Trakt\ApiClient $client, $showId, $season, $episode)
	{
		parent::__construct($client);

		$this->showId = $showId;
		$this->season = $season;
		$this->episode = $episode;
	}

	public function getDetails($subRequests = [])
	{
		$params = [];
		if ($subRequests !== null) {
			$params = ['append_to_response' => implode(',', $subRequests)];
		}

		return $this->client->get("tv/$this->showId/season/$this->season/episode/$this->episode", $params)->toArray();
	}

	public function getCredits()
	{
		return $this->client->get("tv/$this->showId/season/$this->season/episode/$this->episode/credits")->toArray();
	}

	public function getVideos()
	{
		return $this->client->get("tv/$this->showId/season/$this->season/episode/$this->episode/videos")->toArray();
	}
}
