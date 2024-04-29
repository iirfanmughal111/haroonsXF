<?php

namespace FS\AnonymousUser\XF\Pub\Controller;

class Register extends XFCP_Register
{
    protected function setupRegistration(array $input)
    {
        if (strtolower($input['username']) == "anonymous") {
            throw $this->exception(
                $this->error(\XF::phrase('fs_anonymous_username'))
            );
        }
        return parent::setupRegistration($input);
    }
}
