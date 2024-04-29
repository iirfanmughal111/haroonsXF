<?php

namespace nick97\TraktMovies\Trakt;

use nick97\TraktMovies\Trakt\Api\ApiResponse;

class ApiClient
{
	protected $language;
	protected $licenseKey;

	const URL = 'https://api.themoviedb.org/3/';

	protected $error = [];

	public function __construct($licenseKey, $language)
	{
		$this->licenseKey = $licenseKey;
		$this->language = $language;
	}

	public function hasError()
	{
		return (bool) $this->error;
	}

	public function getError()
	{
		return $this->error;
	}

	public function getMovie($movieId)
	{
		return new \nick97\TraktMovies\Trakt\Api\Movies($this, $movieId);
	}

	public function getChanges()
	{
		return new \nick97\TraktMovies\Trakt\Api\Changes($this);
	}

	public function getGenres()
	{
		return new \nick97\TraktMovies\Trakt\Api\Genres($this);
	}

	public function getPeople()
	{
		return new \nick97\TraktMovies\Trakt\Api\People($this);
	}

	public function getCompany()
	{
		return new \nick97\TraktMovies\Trakt\Api\Company($this);
	}

	public function get($path, $params = [], $options = [])
	{
		return $this->request(self::URL . $path, $params, $options);
	}

	public function request($url, $params = [], $options = [])
	{
		$params = array_merge([
			'api_key' => $this->licenseKey,
			'language' => $this->language,
		], $params);

		$response = null;
		try {
			$response = \XF::app()->http()->client()->get($url . '?' . http_build_query($params), $options);
		} catch (\GuzzleHttp\Exception\RequestException $e) {
			if (null !== $e->getResponse()) {
				$error = 'Trakt Error ' . $e->getResponse()->getStatusCode();
				$error .= ': ' . $e->getResponse()->getReasonPhrase();
			} else {
				$error = $e->getMessage();
			}

			$this->error = $error;
		}

		return new ApiResponse($response);
	}
}
