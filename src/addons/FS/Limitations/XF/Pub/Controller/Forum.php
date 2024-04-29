<?php

namespace FS\Limitations\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Forum extends XFCP_Forum
{
    public function actionPostThread(ParameterBag $params)
    {
        if (!$params->node_id && !$params->node_name) {
            return $this->rerouteController('XF:Forum', 'postThreadChooser');
        }

        $forum = $this->assertViewableForum($params->node_id ?: $params->node_name, ['DraftThreads|' . \XF::visitor()->user_id]);

        $isPreRegThread = $forum->canCreateThreadPreReg();

        if (!$forum->canCreateThread($error) && !$isPreRegThread) {
            return $this->noPermission($error);
        }

        $visitor = \XF::visitor();

        // $visitorDailyDiscussionCount = $visitor->daily_discussion_count;
        // $dailyDiscussionLimit = $visitor->hasPermission('fs_limitations', 'fs_dailyDiscussiontLimit');

        $upgradeUrl = [
            'upgradeUrl' => $this->buildLink('account-upgrade/')
        ];

        // -------------------------- Check Daily Post Limits --------------------------

        // if (($dailyDiscussionLimit >= 0) && $visitorDailyDiscussionCount >= $dailyDiscussionLimit) {

        //     if ($dailyDiscussionLimit == 0)
        //         throw $this->exception($this->notFound(\XF::phrase('fs_l_discussion_not_allowed_please_upgrade', ['upgradeUrl' => $this->buildLink('account-upgrade/')])));

        //     $params = [
        //         'visitorDailyDiscussionCount' => $visitorDailyDiscussionCount,
        //         'dailyDiscussionLimit'   => $dailyDiscussionLimit,
        //         'upgradeUrl' => $this->buildLink('account-upgrade/')
        //     ];

        //     throw $this->exception($this->notFound(\XF::phrase('fs_l_daily_discussion_limit_reached_please_upgrade', $params)));
        // }

        // -------------------------- Check Daily Ads Limits --------------------------

        $secondary_group_ids = $visitor['secondary_group_ids'];
        $secondary_group_ids[] = $visitor['user_group_id'];

        $finder = $this->finder('FS\Limitations:Limitations')->where('user_group_id', $secondary_group_ids)->order('daily_ads', 'DESC')->fetchOne();

        if ($finder) {

            $nodeIds = explode(",", $finder['node_ids']);

            if (in_array($forum->node_id, $nodeIds)) {

                if ($visitor['daily_ads'] >= $finder['daily_ads']) {
                    throw $this->exception($this->notFound(\XF::phrase('fs_limitations_daily_ads_limit_reached', $upgradeUrl)));
                }

                $parent = parent::actionPostThread($params);

                if ($this->isPost()) {

                    $increment = $visitor->daily_ads + 1;

                    $visitor->fastUpdate('daily_ads', $increment);
                }

                return $parent;
            }
        }

        $finder = $this->finder('FS\Limitations:Limitations')->fetch();

        if (count($finder) > 0) {

            $existed = false;

            foreach ($finder as $single) {
                $nodeIds = explode(",", $single['node_ids']);

                if (in_array($forum->node_id, $nodeIds)) {
                    $existed = $single['user_group_id'];
                }
            }

            if ($existed) {
                if (!in_array($existed, $secondary_group_ids)) {
                    throw $this->exception($this->notFound(\XF::phrase('fs_limitations_daily_ads_not_permission', $upgradeUrl)));
                }
            }
        }

        return parent::actionPostThread($params);
    }
}
