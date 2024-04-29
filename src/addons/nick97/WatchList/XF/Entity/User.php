<?php

namespace nick97\WatchList\XF\Entity;

use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\Entity\Structure;

/**
 * @property int th_name_color_id
 * @property int th_view_count
 * @property ArrayCollection Trophies
 * @property ArrayCollection|null THUIUsernameHistory
 */
class User extends XFCP_User
{

    public function canViewWatchList(&$error = null)
    {
        $visitor = \XF::visitor();

        if (!$this->isPrivacyCheckMet('allow_view_watchlist', $visitor)) {
            $error = \XF::phraseDeferred('nick97_member_limits_viewing_watchlist');
            return false;
        }

        return true;
    }

    public function canViewStats(&$error = null)
    {
        $visitor = \XF::visitor();

        if (!$this->isPrivacyCheckMet('allow_view_stats', $visitor)) {
            $error = \XF::phraseDeferred('nick97_member_limits_viewing_stats');
            return false;
        }

        return true;
    }
}
