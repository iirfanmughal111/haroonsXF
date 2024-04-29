<?php

namespace FS\RegTermsAndConditions\XF\Pub\Controller;

class Register extends XFCP_Register
{
    public function actionIndex()
    {
        if (!$this->filter('accept', 'bool')) {
            return $this->view('XF:Register\Form', 'fs_registration_terms_conditions', []);
        }

        return parent::actionIndex();
    }
}
