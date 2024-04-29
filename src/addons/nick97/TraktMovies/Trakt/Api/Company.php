<?php

namespace nick97\TraktMovies\Trakt\Api;

class Company extends AbstractApiSection
{
	public function getDetails($companyId)
	{
		return $this->client->get("company/$companyId")->toArray();
	}
}
