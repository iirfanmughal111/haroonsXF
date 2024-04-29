<?php

namespace Snog\Movies\Tmdb\Api;

class Company extends AbstractApiSection
{
	public function getDetails($companyId)
	{
		return $this->client->get("company/$companyId")->toArray();
	}
}
