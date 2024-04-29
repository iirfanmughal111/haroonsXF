<?php

namespace nick97\TraktIntegration\Helper;

class Tmdb extends \Snog\Movies\Helper\Tmdb
{
	public function parseMovieId($url): int
	{
		if (stristr($url, 'themoviedb')) {
			// preg_match_all USED FOR FUTURE API PARAMETER CAPTURING
			preg_match_all('/\d+/', $url, $matches);

			if (!empty($matches[0][0])) {
				$movieId = intval($matches[0][0]);
			} else {
				$movieId = 0;
			}
		} else if (stristr($url, 'trakt.tv/movies')) {
			// preg_match_all USED FOR FUTURE API PARAMETER CAPTURING
			$pattern = "/https:\/\/trakt\.tv\/movies\//";
			$cleanUrl = preg_replace($pattern, "", $url);

			if (!empty($cleanUrl)) {
				$tmdbId = $this->getIdFromTrakt($cleanUrl);
				$movieId = intval($tmdbId);
			} else {
				$movieId = 0;
			}
		} else if (is_numeric($url)) {
			if (intval($url) == 0) {
				$movieId = 0;
			} else {
				$movieId = $url;
			}
		} else {
			$movieId = 0;
		}

		return $movieId;
	}

	protected function getIdFromTrakt($id)
	{
		$endpoint = 'https://api.trakt.tv/movies/' . $id;

		$clientKey = \XF::options()->nick97_trakt_api_key;

		if (!$clientKey) {
			throw new \XF\PrintableException(\XF::phrase('nick97_trakt_api_key_not_found'));
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
			$recordExist = \XF::finder('nick97\TraktIntegration:TraktMovSlug')->where('tmdb_id', $movieId)->fetchOne();

			if (!$recordExist) {
				$insertData = \XF::em()->create('nick97\TraktIntegration:TraktMovSlug');

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
			throw new \XF\PrintableException(\XF::phrase('nick97_trakt_request_not_found'));
		} elseif ($statusCode == 401) {
			throw new \XF\PrintableException(\XF::phrase('nick97_trakt_request_unauthorized'));
		} elseif ($statusCode == 415) {
			throw new \XF\PrintableException(\XF::phrase('nick97_trakt_request_unsported_media'));
		} elseif ($statusCode == 400) {
			throw new \XF\PrintableException(\XF::phrase('nick97_trakt_request_empty_body'));
		} elseif ($statusCode == 405) {
			throw new \XF\PrintableException(\XF::phrase('nick97_trakt_request_method_not_allowed'));
		} elseif ($statusCode == 500) {
			throw new \XF\PrintableException(\XF::phrase('nick97_trakt_request_server_error'));
		} elseif ($statusCode != 200) {
			throw new \XF\PrintableException(\XF::phrase('nick97_trakt_request_not_success'));
		}
	}
}
