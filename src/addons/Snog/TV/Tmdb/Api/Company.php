<?php

namespace Snog\TV\Tmdb\Api;


class Company extends AbstractApiSection
{
	public function getDetails($companyId)
	{
		return $this->client->get("company/$companyId")->toArray();
	}
}
