<?php

namespace nick97\TraktMovies\Trakt\Api;

class People extends AbstractApiSection
{
	public function getDetails($personId)
	{
		return $this->client->get("person/$personId")->toArray();
	}
}
