<?php

namespace ThemeHouse\UserImprovements\ConnectedAccount\Provider;

use XF\ConnectedAccount\Storage\StorageState;
use XF\Entity\ConnectedAccountProvider;
use XF\ConnectedAccount\Http\HttpResponseException;
use XF\ConnectedAccount\Provider\AbstractProvider;
use XF\Http\Request;
use XF\Mvc\Controller;

class Steam extends AbstractProvider
{
    public function getOAuthServiceName()
    {
        return 'ThemeHouse\UserImprovements:Service\Steam';
    }

    public function getProviderDataClass()
    {
        return 'ThemeHouse\UserImprovements:ProviderData\Steam';
    }

    public function getDefaultOptions()
    {
        return [
            'key' => ''
        ];
    }

    public function getOAuthConfig(ConnectedAccountProvider $provider, $redirectUri = null)
    {
        return [
            'key' => $provider->options['key'],
            'secret' => '',
            'scopes' => [],
            'redirect' => $redirectUri ?: $this->getRedirectUri($provider)
        ];
    }

    public function parseProviderError(HttpResponseException $e, &$error = null)
    {
        $response = json_decode($e->getResponseContent(), true);
        if (is_array($response) && isset($response['error']['message'])) {
            $e->setMessage($response['error']['message']);
        }
        parent::parseProviderError($e, $error);
    }


    public function requestProviderToken(StorageState $storageState, Request $request, &$error = null, $skipStoredToken = false)
    {
        \XF::dump($request);
        die('hi');
    }

    /**
     * @param Controller $controller
     * @param ConnectedAccountProvider $provider
     * @param $returnUrl
     * @return void|\XF\Mvc\Reply\Redirect
     */
    public function handleAuthorization(Controller $controller, ConnectedAccountProvider $provider, $returnUrl)
    {
        return parent::handleAuthorization($controller, $provider, $returnUrl);
    }
}
