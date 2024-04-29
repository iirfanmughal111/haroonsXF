<?php

namespace Snog\TV\Tmdb\Api;

class Changes extends AbstractApiSection
{
	public function getTvChangeList($page = 1, $params = [])
	{
		$params = array_merge([
			'page' => $page,
		], $params);
		
		return $this->client->get( 'tv/changes', $params)->toArray();
	}
}
