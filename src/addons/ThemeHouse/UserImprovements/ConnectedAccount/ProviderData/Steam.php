<?php

namespace ThemeHouse\UserImprovements\ConnectedAccount\ProviderData;

use \XF\ConnectedAccount\ProviderData\AbstractProviderData;

class Steam extends AbstractProviderData
{
    public function getDefaultEndpoint()
    {
        return '';
    }

    public function getProviderKey()
    {
        $data = $this->requestUserData();
        return $data['id'];
    }

    public function getUsername()
    {
        $data = $this->requestUserData();
        return $data['display_name'];
    }

    public function getEmail()
    {
        $data = $this->requestUserData();
        return $data['email'];
    }

    public function getAvatarUrl()
    {
        $data = $this->requestUserData();
        return $data['profile_image_url'];
    }

    protected function requestUserData()
    {
        $user = $this->requestFromEndpoint('data');
        return $user[0];
    }
}
