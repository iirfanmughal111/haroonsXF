<?php

namespace Snog\TV\Tmdb\Api;

class People extends AbstractApiSection
{
	public function getDetails($personId)
	{
		return $this->client->get("person/$personId")->toArray();
	}
}