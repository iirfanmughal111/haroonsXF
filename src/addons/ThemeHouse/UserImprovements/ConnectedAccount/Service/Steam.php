<?php

namespace ThemeHouse\UserImprovements\ConnectedAccount\Service;

use ThemeHouse\UserImprovements\Vendor\LightOpenID;
use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\Common\Token\TokenInterface;
use OAuth\OAuth2\Service\AbstractService;
use OAuth\Common\Http\Uri\Uri;
use OAuth\Common\Consumer\CredentialsInterface;
use OAuth\Common\Http\Client\ClientInterface;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\Common\Http\Uri\UriInterface;

class Steam extends AbstractService
{
    public function __construct(
        CredentialsInterface $credentials,
        ClientInterface $httpClient,
        TokenStorageInterface $storage,
        $scopes = array(),
        UriInterface $baseApiUri = null
    ) {
        parent::__construct($credentials, $httpClient, $storage, $scopes, $baseApiUri);

        if (null === $baseApiUri) {
            $this->baseApiUri = new Uri('http://steamcommunity.com/openid/');
        }
    }

    public function request($path, $method = 'GET', $body = null, array $extraHeaders = array())
    {
    }

    /**
     * Parses the access token response and returns a TokenInterface.
     *
     *
     * @param string $responseBody
     *
     * @return TokenInterface
     *
     * @throws TokenResponseException
     */
    protected function parseAccessTokenResponse($responseBody)
    {
        var_dump($responseBody);
        die('Hi');
        // TODO: Implement parseAccessTokenResponse() method.
    }

    /**
     * Returns the authorization API endpoint.
     *
     * @return UriInterface
     */
    public function getAuthorizationEndpoint()
    {
        $openid = new LightOpenID('localhost:1337');
        $openid->identity = 'http://steamcommunity.com/openid';

        return new Uri($openid->authUrl());
    }

    /**
     * Returns the access token API endpoint.
     *
     * @return UriInterface
     */
    public function getAccessTokenEndpoint()
    {
        // TODO: Implement getAccessTokenEndpoint() method.
    }
}
