<?php

namespace nick97\TraktIntegration\Helper\Tmdb;

class Show extends \Snog\TV\Helper\Tmdb\Show
{
	public function parseShowId($url)
	{
		$showId = 0;
		if (stristr($url, 'themoviedb')) {
			// preg_match_all USED FOR FUTURE API PARAMETER CAPTURING
			preg_match_all('/\d+/', $url, $matches);

			if (!empty($matches[0][0])) {
				$showId = $matches[0][0];
			} elseif (stristr($url, '?')) {
				$showIdParts = explode('?', $url);
				if (!empty($showIdParts[0])) {
					$showId = $showIdParts[0];
				}
			}
		} else if (stristr($url, 'trakt.tv/shows')) {
			// preg_match_all USED FOR FUTURE API PARAMETER CAPTURING
			$pattern = "/https:\/\/trakt\.tv\/shows\//";
			$cleanUrl = preg_replace($pattern, "", $url);

			if (!empty($cleanUrl)) {
				$tmdbId = $this->getIdFromTrakt($cleanUrl);
				$showId = intval($tmdbId);
			} elseif (stristr($url, '?')) {
				$showIdParts = explode('?', $url);
				if (!empty($showIdParts[0])) {
					$tmdbId = $this->getIdFromTrakt($showIdParts[0]);
					$showId = intval($tmdbId);
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

			$recordExist = \XF::finder('nick97\TraktIntegration:TraktTVSlug')->where('tmdb_id', $movieId)->fetchOne();

			if (!$recordExist) {
				$insertData = \XF::em()->create('nick97\TraktIntegration:TraktTVSlug');

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
