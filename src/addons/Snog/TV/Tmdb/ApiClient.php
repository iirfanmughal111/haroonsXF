<?php

namespace Snog\TV\Tmdb;

use Snog\TV\Tmdb\Api\ApiResponse;

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

	public function getTv($showId)
	{
		return new \Snog\TV\Tmdb\Api\TV($this, $showId);
	}

	public function getAiringToday()
	{
		return new \Snog\TV\Tmdb\Api\AiringToday($this);
	}

	public function getChanges()
	{
		return new \Snog\TV\Tmdb\Api\Changes($this);
	}

	public function getGenres()
	{
		return new \Snog\TV\Tmdb\Api\Genres($this);
	}

	public function getPeople()
	{
		return new \Snog\TV\Tmdb\Api\People($this);
	}

	public function getCompany()
	{
		return new \Snog\TV\Tmdb\Api\Company($this);
	}

	public function getNetwork()
	{
		return new \Snog\TV\Tmdb\Api\Network($this);
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
		try
		{
			$response = \XF::app()->http()->client()->get($url . '?' . http_build_query($params), $options);
		}
		catch (\GuzzleHttp\Exception\RequestException $e)
		{
			if (null !== $e->getResponse())
			{
				$error = 'TMDb Error ' . $e->getResponse()->getStatusCode();
				$error .= ': ' . $e->getResponse()->getReasonPhrase();
			}
			else
			{
				$error = $e->getMessage();
			}

			$this->error = $error;
		}

		return new ApiResponse($response);
	}
}
