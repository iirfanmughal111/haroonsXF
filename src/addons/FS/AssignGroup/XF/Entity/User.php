<?php

namespace FS\AssignGroup\XF\Entity;

use XF\Mvc\Entity\Structure;

class User extends XFCP_User
{
    protected function _setupDefaults()
    {
        $options = \XF::options();
        if (!$options->fs_assign_group_applicable) {
            return parent::_setupDefaults();
        }

        $defaults = $options->registrationDefaults;
        $this->visible = $defaults['visible'] ? true : false;
        $this->activity_visible = $defaults['activity_visible'] ? true : false;

        $this->user_group_id = $options->fs_assign_group_applicable;
        $this->timezone = $options->guestTimeZone;
        $this->language_id = \XF::language()->getId();

        $this->last_summary_email_date = $defaults['receive_admin_email'] ? \XF::$time : null;
    }
}
