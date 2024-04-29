<?php

namespace FS\RegistrationConfirmPassword\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Register extends XFCP_Register
{
    protected function getRegistrationInput(\XF\Service\User\RegisterForm $regForm)
    {
        $input = parent::getRegistrationInput($regForm);
        $input['password_confirm'] = $this->filter('password_confirm', 'str');
        return $input;
    }
}
