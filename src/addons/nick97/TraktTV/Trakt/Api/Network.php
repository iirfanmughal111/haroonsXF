<?php

namespace nick97\TraktTV\Trakt\Api;

class Network extends AbstractApiSection
{
	public function getDetails($companyId)
	{
		return $this->client->get("network/$companyId")->toArray();
	}
}
