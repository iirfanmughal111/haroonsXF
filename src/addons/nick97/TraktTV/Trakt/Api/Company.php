<?php

namespace nick97\TraktTV\Trakt\Api;


class Company extends AbstractApiSection
{
	public function getDetails($companyId)
	{
		return $this->client->get("company/$companyId")->toArray();
	}
}
