<?php

namespace FS\UpgradeUserGroup\Cron;


class UpgradeUserGroupCron
{

    public static function upgradeUsergroup()
    {
        $finder = \XF::finder('FS\UpgradeUserGroup:UpgradeUserGroup')->where('last_login', null)->fetch();

        foreach ($finder as $value) {
            $conditions = [
                ['user_group_id', $value->current_userGroup],
              
            ];

            $finderUsergroup = \XF::finder('XF:User')->where('message_count', '>=', $value->message_count)->whereOr($conditions)->fetch();

            self::update_user_groups($finderUsergroup, $value);
        }
    }

    public static function downgradeUsergroup()
    {
        $finder = \XF::finder('FS\UpgradeUserGroup:UpgradeUserGroup')->where('message_count', null)->fetch();

        foreach ($finder as $value) {
            $conditions = [
                ['user_group_id', $value->current_userGroup],
                ['secondary_group_ids', 'LIKE', '%' . $value->current_userGroup . '%'],
            ];

            $finderUsergroup = \XF::finder('XF:User')->where('last_activity', '<=', time() - ($value->last_login * 86400))->whereOr($conditions)->fetch();

            self::update_user_groups($finderUsergroup, $value);
        }
    }

    public static function update_user_groups($finderUsergroup, $value)
    {
        foreach ($finderUsergroup as $user) {

            if ($user->user_group_id == $value->current_userGroup) {


                if (in_array($value->upgrade_userGroup, $user->secondary_group_ids)) {

                    $userGroupIds = $user->secondary_group_ids;

                    $new_userGroups = array_diff($userGroupIds, array($value->upgrade_userGroup));

                    $user->fastUpdate('secondary_group_ids', $new_userGroups);
                }
            } else {

                $userGroupIds = $user->secondary_group_ids;

                $new_userGroups = array_diff($userGroupIds, array($value->current_userGroup));
                if (!in_array($value->upgrade_userGroup, $new_userGroups)) {
                    if ($user->user_group_id != $value->upgrade_userGroup) {
                        array_push($new_userGroups, $value->upgrade_userGroup);
                    }
                }

                $user->fastUpdate('secondary_group_ids', $new_userGroups);
            }
        }
    }
}
