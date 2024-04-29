<?php

namespace Snog\TV\Tmdb;

class Image
{
	const URL = 'https://image.tmdb.org/t/p/';

	protected $error;

	public function hasError()
	{
		return (bool) $this->error;
	}

	public function getError()
	{
		return $this->error;
	}

	public function getImage($srcPath, $size = 'original', &$lastModified = null)
	{
		$response = $this->request($this->getImageUrl($srcPath, $size));
		if ($response)
		{
			$lastModified = $response->getHeader('last-modified');
			$lastModified = reset($lastModified);

			return $response->getBody();
		}

		return null;
	}

	public function getImageUrl($srcPath, $size = 'original')
	{
		return self::URL . $size . $srcPath;
	}

	public function getImageLatestModifiedDate($srcPath, $size = 'original')
	{
		$response = $this->request(self::URL. $size . $srcPath);
		if ($response)
		{
			$lastModified = $response->getHeader('last-modified');
			return reset($lastModified);
		}

		return null;
	}

	protected function request($url, $params = [], $options = [])
	{
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

		return $response ?? null;
	}
}