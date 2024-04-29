<?php

namespace nick97\TraktTV\Helper\Trakt;

class Show
{
	public function parseShowId($url)
	{
		$showId = 0;
		if (stristr($url, 'trakt.tv/')) {
			// preg_match_all USED FOR FUTURE API PARAMETER CAPTURING
			$pattern = "/https:\/\/trakt\.tv\/shows\//";
			$cleanUrl = preg_replace($pattern, "", $url);

			if (!empty($cleanUrl)) {
				$tmdbId = $this->getIdFromTrakt($cleanUrl);
				$showId = intval($tmdbId);
			} elseif (stristr($url, '?')) {
				$showIdParts = explode('?', $url);
				if (!empty($showIdParts[0])) {
					$showId = $showIdParts[0];
				}
			}
		} else if (is_numeric($url)) {
			$showId = intval($url);
		}

		return $showId;
	}

	protected function getIdFromTrakt($id)
	{
		$endpoint = 'https://api.trakt.tv/shows/' . $id;

		$clientKey = \XF::options()->traktTvThreads_clientkey;

		if (!$clientKey) {
			throw new \XF\PrintableException(\XF::phrase('nick97_tv_trakt_api_key_not_found'));
		}

		$headers = array(
			'Content-Type: application/json',
			'trakt-api-version: 2',
			'trakt-api-key: ' . $clientKey
		);

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $endpoint);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);

		$resCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		$this->CheckRequestError($resCode);

		curl_close($ch);

		$toArray = json_decode($result, true);

		$movieId = $toArray["ids"]["tmdb"];

		if (isset($movieId)) {

			$recordExist = \XF::finder('nick97\TraktTV:TraktTVSlug')->where('tmdb_id', $movieId)->fetchOne();

			if (!$recordExist) {
				$insertData = \XF::em()->create('nick97\TraktTV:TraktTVSlug');

				$insertData->tmdb_id = $movieId;
				$insertData->trakt_slug = $toArray["ids"]["slug"];
				$insertData->save();
			}
			return $movieId;
		} else {
			return 0;
		}
	}

	protected function CheckRequestError($statusCode)
	{
		if ($statusCode == 404) {
			throw new \XF\PrintableException(\XF::phrase('nick97_trakt_movies_request_not_found'));
		} elseif ($statusCode == 401) {
			throw new \XF\PrintableException(\XF::phrase('nick97_trakt_movies_request_unauthorized'));
		} elseif ($statusCode == 415) {
			throw new \XF\PrintableException(\XF::phrase('nick97_trakt_movies_request_unsported_media'));
		} elseif ($statusCode == 400) {
			throw new \XF\PrintableException(\XF::phrase('nick97_trakt_movies_request_empty_body'));
		} elseif ($statusCode == 405) {
			throw new \XF\PrintableException(\XF::phrase('nick97_trakt_movies_request_method_not_allowed'));
		} elseif ($statusCode == 500) {
			throw new \XF\PrintableException(\XF::phrase('nick97_trakt_movies_request_server_error'));
		} elseif ($statusCode != 200) {
			throw new \XF\PrintableException(\XF::phrase('nick97_trakt_movies_request_not_success'));
		}
	}

	public function parseGenres(array $apiResponse): array
	{
		$genres = [];
		if (isset($apiResponse['genres'])) {
			foreach ($apiResponse['genres'] as $genre) {
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
		if (isset($apiResponse['created_by'])) {
			foreach ($apiResponse['created_by'] as $director) {
				$directors[] = $director['name'];
			}
		}

		return $directors;
	}

	public function getDirectorsList(array $apiResponse)
	{
		return implode(',', $this->parseDirectors($apiResponse));
	}

	public function parseCast(array $apiData): array
	{
		$cast = [];
		if (isset($apiData['credits'])) {
			foreach ($apiData['credits']['cast'] as $member) {
				$cast[] = $member['name'];
			}
		}

		return $cast;
	}

	public function getCastList(array $apiResponse)
	{
		return implode(',', $this->parseCast($apiResponse));
	}

	public function parseTrailers(array $apiResponse, string $hosting = 'YouTube'): array
	{
		$trailers = [];

		if (isset($apiResponse['videos']['results'])) {
			foreach ($apiResponse['videos']['results'] as $video) {
				if ($video['site'] == $hosting) {
					$trailers[] = $video['key'];
				}
			}
		}

		return $trailers;
	}

	public function getTrailer($apiResponse, $hosting = 'YouTube')
	{
		$trailers = $this->parseTrailers($apiResponse, $hosting);
		return reset($trailers) ?: '';
	}

	public function getProductionCompanies(array $apiResponse)
	{
		$companies = [];
		if (isset($apiResponse['production_companies'])) {
			$companies = $apiResponse['production_companies'];
		}

		return $companies;
	}

	public function getNetworks(array $apiResponse)
	{
		$companies = [];
		if (isset($apiResponse['networks'])) {
			$companies = $apiResponse['networks'];
		}

		return $companies;
	}

	public function getWatchProviders(array $apiResponse)
	{
		$providers = [];
		if (isset($apiResponse['watch/providers']['results'])) {
			$providers = $apiResponse['watch/providers']['results'];
		}

		return $providers;
	}

	public function getStandardizedTvData($threadId, array $apiResponse)
	{
		$app = \XF::app();

		return [
			'tv_id' => $apiResponse['id'] ?? 0,
			'imdb_id' => $apiResponse['external_ids']['imdb_id'] ?? '',
			'thread_id' => $threadId,
			'tv_image' => $app->applyExternalDataUrl('tv/LargePosters' . $apiResponse['poster_path'] ?: '/no-poster.jpg', true),
			'tv_genres' => $this->getGenresList($apiResponse),
			'tv_director' => $this->getDirectorsList($apiResponse),
			'tv_cast' => $this->getCastList($apiResponse),
			'tv_release' => $apiResponse['first_air_date'] ?? '',
			'tv_title' => $apiResponse['name'] ?? '',
			'tv_plot' => $apiResponse['overview'] ?? '',
			'tv_trailer' => $this->getTrailer($apiResponse),
			'first_air_date' => isset($apiResponse['first_air_date']) ? strtotime($apiResponse['first_air_date']) : 0,
			'last_air_date' => isset($apiResponse['last_air_date']) ? strtotime($apiResponse['last_air_date']) : 0,
			'status' => $apiResponse['status'] ?? '',
		];
	}
}
