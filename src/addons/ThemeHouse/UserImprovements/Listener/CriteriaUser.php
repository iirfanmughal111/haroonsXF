<?php

namespace ThemeHouse\UserImprovements\Listener;

use XF\Entity\User;

class CriteriaUser
{
    public static function criteriaUser($rule, array $data, User $user, &$returnValue)
    {
        switch ($rule) {
            /* User name color */
            case 'th_username_color':
            case 'kl_ui_username_color':
                if ($user->th_name_color_id) {
                    $returnValue = true;
                }
                break;

            /* User name color */
            case 'th_no_username_color':
            case 'kl_ui_no_username_color':
                if (!$user->th_name_color_id) {
                    $returnValue = true;
                }
                break;


            /* User name changes */
            case 'th_min_username_changes':
            case 'kl_ui_min_username_changes':
                if ($user->UsernameHistory->count() >= $data['count']) {
                    $returnValue = true;
                }
                break;

            /* User name changes */
            case 'th_max_username_changes':
            case 'kl_ui_max_username_changes':
                if ($user->UsernameHistory->count() <= $data['count']) {
                    $returnValue = true;
                }
                break;

            /* Profile views */
            case 'th_min_profile_views':
            case 'kl_ui_min_profile_views':
                if ($user->th_view_count >= $data['count']) {
                    $returnValue = true;
                }
                break;

            /* Max profile views */
            case 'th_max_profile_views':
            case 'kl_ui_max_profile_views':
                if ($user->th_view_count < $data['count']) {
                    $returnValue = true;
                }
                break;
        }
    }
}
