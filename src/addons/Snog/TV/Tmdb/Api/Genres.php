<?php

namespace Snog\TV\Tmdb\Api;

class Genres extends AbstractApiSection
{
	public function getTvList()
	{
		return $this->client->get( 'genre/tv/list')->toArray();
	}
}