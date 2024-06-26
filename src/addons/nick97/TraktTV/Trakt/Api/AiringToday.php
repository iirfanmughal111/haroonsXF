<?php

namespace nick97\TraktTV\Trakt\Api;

class AiringToday extends AbstractApiSection
{
	public function getList($page = 1, $params = [])
	{
		$params = array_merge([
			'page' => $page,
		], $params);

		return $this->client->get('tv/airing_today', $params)->toArray();
	}
}
