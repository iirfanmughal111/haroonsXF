<?php

namespace nick97\TraktMovies\Helper;

class Movie
{
	protected function standardizeMovieData($input)
	{
		return array_replace([
			'thread_id' => 0,
			'trakt_id' => 0,
			'imdb_id' => 0,
			'trakt_title' => '',
			'trakt_plot' => '',
			'trakt_image' => '',
			'trakt_genres' => '',
			'trakt_director' => '',
			'trakt_cast' => '',
			'trakt_release' => '',
			'trakt_tagline' => '',
			'trakt_runtime' => 0,
			'trakt_rating' => 0,
			'trakt_votes' => 0,
			'trakt_trailer' => '',
			'trakt_status' => '',
			'trakt_popularity' => 0,
			'comment' => '',
		], $input);
	}

	public function getDefaultDetailsSubRequests()
	{
		return [];
	}
}
