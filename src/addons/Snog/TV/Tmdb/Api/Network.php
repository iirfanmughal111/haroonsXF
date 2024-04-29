<?php

namespace Snog\TV\Tmdb\Api;

class Network extends AbstractApiSection
{
	public function getDetails($companyId)
	{
		return $this->client->get("network/$companyId")->toArray();
	}
}
