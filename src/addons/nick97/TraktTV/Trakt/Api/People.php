<?php

namespace nick97\TraktTV\Trakt\Api;

class People extends AbstractApiSection
{
	public function getDetails($personId)
	{
		return $this->client->get("person/$personId")->toArray();
	}
}
