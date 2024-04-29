<?php

namespace nick97\TraktTV\Trakt\Api;

use Psr\Http\Message\ResponseInterface;

class ApiResponse
{
	protected $response;

	public function __construct($response)
	{
		$this->response = $response;
	}

	public function getRaw()
	{
		$response = $this->response;
		return $response instanceof ResponseInterface ? $response->getBody()->getContents() : '';
	}

	public function toArray()
	{
		$response = $this->response;
		return  $response instanceof ResponseInterface ? \json_decode($response->getBody(), true) : [];
	}
}
