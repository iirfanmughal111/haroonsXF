<?php

namespace FS\QuestionAnswers\XF\Repository;

use XF\Mvc\Entity\Structure;

class Forum extends XFCP_Forum
{
    public function getForumCounterTotals()
    {

        $forumTotal = parent::getForumCounterTotals();

        $questionForumId = intval(\XF::options()->fs_questionAnswerForum);

        return $this->db()->fetchRow("
			SELECT SUM(discussion_count) AS threads,
				SUM(message_count) AS messages
			FROM xf_forum WHERE node_id != $questionForumId
		");

        return $forumTotal;
    }
}
