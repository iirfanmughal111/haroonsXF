<?php

namespace Snog\Movies\Tmdb\Api;

class Genres extends AbstractApiSection
{
	public function getMovieList()
	{
		return $this->client->get('genre/movie/list')->toArray();
	}
}
