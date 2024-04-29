<?php

namespace FS\HideContentWithPreview\XF\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Mvc\Reply\View;

class Thread extends XFCP_Thread
{
    public function actionIndex(ParameterBag $params)
    {
        $parent = parent::actionIndex($params);

        if ($parent instanceof \XF\Mvc\Reply\View) {
            $visitor = \XF::visitor();
            $options = \XF::options();

            $userGroupIds = $options->fs_hcwp_userGroups;

            $posts = $parent->getParam('posts');
            foreach ($posts as $post) {

                if (!$visitor->isMemberOf($userGroupIds)) {
                    $this->filterPost($post);
                } elseif ($visitor['register_date'] <= (time() - (24 * 60 * 60))) {
                    $pattern = "/\[\s*locked\s*=\s*(\d+)\s*\]/i";

                    if (preg_match($pattern, $post->message, $matches)) {

                        $postCount = $matches[1];

                        if ($visitor['message_count'] >= $postCount) {
                            $this->filterPost($post);
                        }
                    }
                }
            }

        }

        return $parent;
    }

    protected function filterPost($post)
    {
        $pattern = "/\[\s*locked\s*=\s*(\d+)\s*\]/i";
        if (preg_match($pattern, $post->message)) // Check if [locked] tags exist in message
        {
            $rmPattern = "/\[\s*locked\s*=\s*\d+\]\s*(.*?)\s*\[\/locked\]/si";

            $customizedMessage = preg_replace($rmPattern, '$1', $post->message); // Remove [locked] tags

            $post->message = $customizedMessage;
        }
    }
}
