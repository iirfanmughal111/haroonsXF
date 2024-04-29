<?php

namespace FS\SecurityQuestion\Pub\Controller;

use XF\Mvc\ParameterBag;

class LostPassword extends XFCP_LostPassword
{
    public function actionIndex()
    {
       
        
        if(!$this->isPost()){
            
            return $this->view('XF:LostPassword\Index', 'how_to_verify');
        }
        
        return parent::actionIndex();
    }

    public function actionConfirm(ParameterBag $params)
    {
        /** @var \XF\Entity\User $user */
        $user = $this->assertRecordExists('XF:User', $params->user_id);

        /** @var \XF\Service\User\PasswordReset $lostPassword */
        $lostPassword = $this->service('XF:User\PasswordReset', $user);

        $confirmationKey = $this->filter('c', 'str');

        if ($this->isPost()) {
            $passwords = $this->filter([
                'password' => 'str',
                'password_confirm' => 'str'
            ]);

            if (!$passwords['password']) {
                return $this->error(\XF::phrase('please_enter_valid_password'));
            }

            if (!$passwords['password_confirm'] || $passwords['password'] !== $passwords['password_confirm']) {
                return $this->error(\XF::phrase('passwords_did_not_match'));
            }

            $lostPassword->resetLostPassword($passwords['password']);

            if (!\XF::visitor()->user_id) {
                /** @var \XF\ControllerPlugin\Login $loginPlugin */
                $loginPlugin = $this->plugin('XF:Login');
                $loginPlugin->triggerIfTfaConfirmationRequired(
                    $user,
                    $this->buildLink('login/two-step', null, [
                        '_xfRedirect' => $this->buildLink('index')
                    ])
                );

                $this->session()->changeUser($user);
            }

            return $this->redirect($this->buildLink('index'), \XF::phrase('your_password_has_been_reset'));
        } else {
            $viewParams = [
                'user' => $user,
                'c' => $confirmationKey
            ];
            return $this->view('XF:LostPassword\Confirm', 'lost_password_confirm', $viewParams);
        }
    }
}
