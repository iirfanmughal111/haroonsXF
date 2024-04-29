<?php

namespace nick97\TraktMovies\Trakt\Api;

class Changes extends AbstractApiSection
{
	public function getMoviesChangeList($page = 1, $params = [])
	{
		$params = array_merge([
			'page' => $page,
		], $params);

		return $this->client->get('movie/changes', $params)->toArray();
	}
}
