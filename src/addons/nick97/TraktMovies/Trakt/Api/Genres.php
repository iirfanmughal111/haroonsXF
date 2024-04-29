<?php

namespace nick97\TraktMovies\Trakt\Api;

class Genres extends AbstractApiSection
{
	public function getMovieList()
	{
		return $this->client->get('genre/movie/list')->toArray();
	}
}
