<?php

namespace FS\BitcoinIntegration\XF\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;
use XF\Payment\AbstractProvider;


class UserUpgrade extends XFCP_UserUpgrade
{
    public function getUserUpgradeExit()
    {
        $userUpgradeActive = $this->finder('XF:UserUpgradeActive')->where('user_upgrade_id', $this->user_upgrade_id)->where('user_id', \xf::visitor()->user_id)->fetchOne();
        if ($userUpgradeActive) {

            return true;
        }
        return false;
    }
}
