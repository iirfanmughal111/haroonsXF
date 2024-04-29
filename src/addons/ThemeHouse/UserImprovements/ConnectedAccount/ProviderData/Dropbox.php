<?php

namespace ThemeHouse\UserImprovements\ConnectedAccount\ProviderData;

use \XF\ConnectedAccount\ProviderData\AbstractProviderData;

class Dropbox extends AbstractProviderData
{
    /**
     * @return string
     */
    public function getDefaultEndpoint()
    {
        return 'users/get_current_account';
    }

    /**
     * @return mixed|null
     */
    public function getProviderKey()
    {
        return $this->requestFromEndpoint('account_id', 'POST');
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->requestFromEndpoint('name', 'POST')['display_name'];
    }

    /**
     * @return string
     */
    public function getAvatarUrl()
    {
        $key = $this->getProviderKey();
        $time = \XF::$time;

        return "https://dl-web.dropbox.com/account_photo/get/{$key}?vers={$time}&size=512x512";
    }
}
