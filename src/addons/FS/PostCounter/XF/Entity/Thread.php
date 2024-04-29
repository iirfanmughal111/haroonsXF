<?php

namespace FS\PostCounter\XF\Entity;

class Thread extends XFCP_Thread
{
	protected function _postDelete()
	{
		parent::_postDelete();

		$nodeId = $this->node_id;

		$visitor = \XF::visitor();
		$db = \XF::db();
		$pcTableName = \XF::em()->getEntityStructure('FS\PostCounter:PostCounter')->table;
		$userId = $visitor->user_id;

		$qry = "UPDATE $pcTableName SET thread_count = thread_count - 1, post_count = post_count - 1 WHERE user_id = $userId and node_id=$nodeId";
		$db->query($qry);
	}
}
