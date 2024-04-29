<?php

namespace Snog\Movies\Helper;

class Tmdb
{


	public function parseMovieId($url): int
	{
		if (stristr($url, 'themoviedb'))
		{
			// preg_match_all USED FOR FUTURE API PARAMETER CAPTURING
			preg_match_all('/\d+/', $url, $matches);

			if (!empty($matches[0][0]))
			{
				$movieId = intval($matches[0][0]);
			}
			else
			{
				$movieId = 0;
			}
		}
		else if (is_numeric($url))
		{
			if (intval($url) == 0)
			{
				$movieId = 0;
			}
			else
			{
				$movieId = $url;
			}
		}
		else
		{
			$movieId = 0;
		}

		return $movieId;
	}

	public function parseGenres(array $apiResponse): array
	{
		$genres = [];
		if (isset($apiResponse['genres']))
		{
			foreach ($apiResponse['genres'] as $genre)
			{
				$genres[] = $genre['name'];
			}
		}

		return $genres;
	}

	public function getGenresList(array $apiResponse)
	{
		return implode(',', $this->parseGenres($apiResponse));
	}

	public function parseDirectors(array $apiResponse): array
	{
		$directors = [];
		if (isset($apiResponse['casts']['crew']))
		{
			foreach ($apiResponse['casts']['crew'] as $director)
			{
				if ($director['job'] == 'Director')
				{
					$directors[] = $director['name'];
				}
			}
		}

		return $directors;
	}

	public function getDirectorsList(array $apiResponse)
	{
		return implode(',', $this->parseDirectors($apiResponse));
	}

	public function parseCast(array $apiResponse): array
	{
		$cast = [];
		if (isset($apiResponse['casts']['cast']))
		{
			foreach ($apiResponse['casts']['cast'] as $actor)
			{
				$cast[] = $actor['name'];
			}
		}

		return $cast;
	}

	public function getCastList(array $apiResponse)
	{
		return implode(',', $this->parseCast($apiResponse));
	}

	public function parseTrailers(array $apiResponse, string $hosting = 'youtube'): array
	{
		$trailers = [];
		if (isset($apiResponse['trailers'][$hosting]))
		{
			foreach ($apiResponse['trailers'][$hosting] as $source)
			{
				$trailers[] = $source['source'];
			}
		}

		return $trailers;
	}

	public function getTrailer(array $apiResponse, string $hosting = 'youtube')
	{
		$trailers = $this->parseTrailers($apiResponse, $hosting);
		return $trailers ? array_values($trailers)[0] : '';
	}

	public function getMovieWatchProviders(array $apiResponse)
	{
		$providers = [];
		if (isset($apiResponse['watch/providers']['results']))
		{
			$providers = $apiResponse['watch/providers']['results'];
		}

		return $providers;
	}

	public function getMovieProductionCompanies(array $apiResponse)
	{
		$companies = [];
		if (isset($apiResponse['production_companies']))
		{
			$companies = $apiResponse['production_companies'];
		}

		return $companies;
	}

	public function getStandardizedTvData(array $apiResponse)
	{
		return [
			'tmdb_title' => $apiResponse['title'] ?? '',
			'tmdb_plot' => isset($apiResponse['overview']) ? html_entity_decode($apiResponse['overview']) : '',
			'tmdb_image' => $apiResponse['poster_path'] ?? '',
			'tmdb_genres' => $this->getGenresList($apiResponse),
			'tmdb_director' => $this->getDirectorsList($apiResponse),
			'tmdb_cast' => $this->getCastList($apiResponse),
			'tmdb_release' => $apiResponse['release_date'] ?? '',
			'tmdb_tagline' => $apiResponse['tagline'] ?? '',
			'tmdb_runtime' => $apiResponse['runtime'] ?? 0,
			'tmdb_status' => $apiResponse['status'] ?? 0,
			'tmdb_trailer' => $this->getTrailer($apiResponse),
			'tmdb_watch_providers' => $this->getMovieWatchProviders($apiResponse),
		];
	}
}