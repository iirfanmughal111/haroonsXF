<?php

namespace FS\QuestionAnswers;

use XF\Entity\User;

class Helper
{
    public static function updateUsersQuestionAnswerCount($questionForumId = null)
    {
        $app = \XF::app();
        $db = \XF::db();

        if (!$questionForumId) {
            $questionForumId = intval(\XF::options()->fs_questionAnswerForum);
        }

        $allQuestionThreadIds = $app->finder('XF:Thread')
            ->where('node_id', $questionForumId)
            ->where('discussion_type', 'question')
            ->pluckFrom('thread_id')->fetch()->toArray();



        $allQuestionThreadIds = implode(',', $allQuestionThreadIds);


        if ($allQuestionThreadIds) {
            $users = $app->finder('XF:User')->where('message_count', '>', 0)->fetch();

            foreach ($users as $user) {
                $userQuestionThreads = $app->finder('XF:Thread')
                    ->where('user_id', $user->user_id)
                    ->where('node_id', $questionForumId)
                    ->where('discussion_type', 'question')
                    ->where('discussion_state', 'visible');


                $questionCount = $userQuestionThreads->total();

                $sql = 'select sum(post_count) as answerCount from xf_thread_user_post where thread_id IN (' . $allQuestionThreadIds . ') AND user_id = ' . $user->user_id;

                $postCount = $db->query($sql)->fetch()['answerCount'];

                $answerCount = ($postCount ? $postCount - $questionCount : 0);


                $user->fastUpdate([
                    'question_count' => $questionCount,
                    'answer_count' => $answerCount
                ]);
            }
        } else {
            $db = \XF::db();
            $db->query('update xf_user set question_count = 0, answer_count = 0');
        }
    }
}
