<?php

namespace Snog\TV\Helper\Tmdb;


class Api
{
	public function getClient()
	{
		$options = \XF::options();
		return new \Snog\TV\Tmdb\ApiClient($options->TvThreads_apikey, $options->TvThreads_language);
	}

	public function getNewClient()
	{
		$options = \XF::options();
		$eventDispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();

		$client = new \Tmdb\Client([
			'api_token' => new \Tmdb\Token\Api\ApiToken($options->TvThreads_apikey),
			'event_dispatcher' => [
				'adapter' => $eventDispatcher
			],

			'http' => [
				'client' => null,
				'request_factory' => null,
				'response_factory' => null,
				'stream_factory' => null,
				'uri_factory' => null,
			]
		]);

		$requestListener = new \Tmdb\Event\Listener\RequestListener($client->getHttpClient(), $eventDispatcher);
		$eventDispatcher->addListener(\Tmdb\Event\RequestEvent::class, $requestListener);

		$languageFilterListener = new \Tmdb\Event\Listener\Request\LanguageFilterRequestListener($options->TvThreads_language);
		$eventDispatcher->addListener(\Tmdb\Event\BeforeRequestEvent::class, $languageFilterListener);

		$apiTokenListener = new \Tmdb\Event\Listener\Request\ApiTokenRequestListener($client->getToken());
		$eventDispatcher->addListener(\Tmdb\Event\BeforeRequestEvent::class, $apiTokenListener);

		$acceptJsonListener = new \Tmdb\Event\Listener\Request\AcceptJsonRequestListener();
		$eventDispatcher->addListener(\Tmdb\Event\BeforeRequestEvent::class, $acceptJsonListener);

		$jsonContentTypeListener = new \Tmdb\Event\Listener\Request\ContentTypeJsonRequestListener();
		$eventDispatcher->addListener(\Tmdb\Event\BeforeRequestEvent::class, $jsonContentTypeListener);

		$userAgentListener = new \Tmdb\Event\Listener\Request\UserAgentRequestListener();
		$eventDispatcher->addListener(\Tmdb\Event\BeforeRequestEvent::class, $userAgentListener);

		return $client;
	}
}
