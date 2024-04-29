<?php

namespace Snog\Movies\Helper;

class Movie
{
	protected function standardizeMovieData($input)
	{
		return array_replace([
			'thread_id' => 0,
			'tmdb_id' => 0,
			'imdb_id' => 0,
			'tmdb_title' => '',
			'tmdb_plot' => '',
			'tmdb_image' => '',
			'tmdb_genres' => '',
			'tmdb_director' => '',
			'tmdb_cast' => '',
			'tmdb_release' => '',
			'tmdb_tagline' => '',
			'tmdb_runtime' => 0,
			'tmdb_rating' => 0,
			'tmdb_votes' => 0,
			'tmdb_trailer' => '',
			'tmdb_status' => '',
			'tmdb_popularity' => 0,
			'comment' => '',
		], $input);
	}

	public function getDefaultDetailsSubRequests()
	{
		return [];
	}
}
