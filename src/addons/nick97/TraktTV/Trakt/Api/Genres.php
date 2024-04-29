<?php

namespace nick97\TraktTV\Trakt\Api;

class Genres extends AbstractApiSection
{
	public function getTvList()
	{
		return $this->client->get('genre/tv/list')->toArray();
	}
}
