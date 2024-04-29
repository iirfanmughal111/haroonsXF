<?php

namespace nick97\TraktTV\Helper\Trakt;


class Api
{
	public function getClient()
	{
		$options = \XF::options();
		return new \nick97\TraktTV\Trakt\ApiClient($options->traktTvThreads_apikey, $options->traktTvThreads_language);
	}

	public function getNewClient()
	{
		$options = \XF::options();
		$eventDispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();

		$client = new \Trakt\Client([
			'api_token' => new \Trakt\Token\Api\ApiToken($options->traktTvThreads_apikey),
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

		$requestListener = new \Trakt\Event\Listener\RequestListener($client->getHttpClient(), $eventDispatcher);
		$eventDispatcher->addListener(\Trakt\Event\RequestEvent::class, $requestListener);

		$languageFilterListener = new \Trakt\Event\Listener\Request\LanguageFilterRequestListener($options->traktTvThreads_language);
		$eventDispatcher->addListener(\Trakt\Event\BeforeRequestEvent::class, $languageFilterListener);

		$apiTokenListener = new \Trakt\Event\Listener\Request\ApiTokenRequestListener($client->getToken());
		$eventDispatcher->addListener(\Trakt\Event\BeforeRequestEvent::class, $apiTokenListener);

		$acceptJsonListener = new \Trakt\Event\Listener\Request\AcceptJsonRequestListener();
		$eventDispatcher->addListener(\Trakt\Event\BeforeRequestEvent::class, $acceptJsonListener);

		$jsonContentTypeListener = new \Trakt\Event\Listener\Request\ContentTypeJsonRequestListener();
		$eventDispatcher->addListener(\Trakt\Event\BeforeRequestEvent::class, $jsonContentTypeListener);

		$userAgentListener = new \Trakt\Event\Listener\Request\UserAgentRequestListener();
		$eventDispatcher->addListener(\Trakt\Event\BeforeRequestEvent::class, $userAgentListener);

		return $client;
	}
}
