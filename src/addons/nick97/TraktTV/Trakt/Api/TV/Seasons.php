<?php

namespace nick97\TraktTV\Trakt\Api\TV;

use nick97\TraktTV\Trakt\Api\AbstractApiSection;

class Seasons extends AbstractApiSection
{
	protected $showId;
	protected $season;

	public function __construct(\nick97\TraktTV\Trakt\ApiClient $client, $showId, $season)
	{
		parent::__construct($client);

		$this->showId = $showId;
		$this->season = $season;
	}

	public function getDetails(array $subRequests = null)
	{
		$params = [];
		if ($subRequests !== null) {
			$params = ['append_to_response' => implode(',', $subRequests)];
		}

		return $this->client->get("tv/$this->showId/season/$this->season", $params)->toArray();
	}

	public function getCredits()
	{
		return $this->client->get("tv/$this->showId/season/$this->season/credits")->toArray();
	}

	public function getVideos()
	{
		return $this->client->get("tv/$this->showId/season/$this->season/videos")->toArray();
	}

	public function getEpisode($episode)
	{
		return new Episodes($this->client, $this->showId, $this->season, $episode);
	}
}
