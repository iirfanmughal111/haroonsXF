<?php

namespace nick97\WatchList\ConnectedAccount\ProviderData;

use \XF\ConnectedAccount\ProviderData\AbstractProviderData;

class Trakt extends AbstractProviderData
{
    /**
     * @return string
     */
    public function getDefaultEndpoint()
    {
        return 'users/settings';
    }

    /**
     * @return mixed
     */
    public function getProviderKey()
    {
        // return $this->requestFromEndpoint('uuid');
        return $this->requestFromEndpoint('slug');
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->requestFromEndpoint('username');
    }

    public function getAvatarUrl()
    {
        $avatarHash = $this->requestFromEndpoint('avatar');

        return $avatarHash;
    }

    public function requestFromEndpoint($key = null, $method = 'GET', $endpoint = null)
    {
        $endpoint = $endpoint ?: $this->getDefaultEndpoint();

        if ($value = $this->requestFromCache($endpoint, $key)) {
            return $value;
        }

        $storageState = $this->storageState;

        $data = $storageState->retrieveProviderData();

        if ($data && $endpoint == $this->getDefaultEndpoint()) {
            if ($key === null) {
                $value = $data;
            } else {
                $value = $data[$key] ?? null;
            }
            $this->storeInCache($endpoint, $value, $key);
            return $value;
        }

        $provider = $storageState->getProvider();
        $handler = $provider->handler;


        try {
            $config = $handler->getOAuthConfig($provider);
            $config['storageType'] = $storageState->getStorageType();

            $accessToken = $storageState->getProviderToken();

            // if ($accessToken->getAccessToken()) {
            if (is_object($accessToken) && method_exists($accessToken, 'getAccessToken')) {
                $accessToken = $accessToken->getAccessToken();
            } else {
                $accessToken = '';
            }

            $response = $this->userRequest($endpoint, $accessToken, $config['key']);

            $responseData = json_decode($response, true);

            if (!$data) {
                $data = [];
                $data['username'] = $responseData['user']['username'];
                $data['uuid'] = $responseData['user']['ids']['uuid'];
                $data['slug'] = $responseData['user']['ids']['slug'];
                $data['avatar'] = $responseData['user']['images']['avatar']['full'];
                $data['verified'] = true;
            }

            $this->storeInCache($endpoint, $data);
            if ($endpoint == $this->getDefaultEndpoint()) {
                $storageState->storeProviderData($data);
            }
            return $this->requestFromCache($endpoint, $key);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function userRequest($endpoint, $bareerToken, $clientId)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.trakt.tv/' . $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $bareerToken,
                'trakt-api-version: 2',
                'trakt-api-key:' . $clientId,
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }
}
