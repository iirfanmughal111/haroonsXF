<?php

namespace nick97\TraktTV\XF\Entity;

/**
 * RELATIONS
 * @property \nick97\TraktTV\Entity\TVForum $TVForum
 */
class Node extends XFCP_Node
{
	public function getNewTv($cascadeSave = true)
	{
		/** @var \nick97\TraktTV\Entity\TVForum $TVForum */
		$TVForum = $this->_em->create('nick97\TraktTV:TVForum');

		$TVForum->node_id = $this->_getDeferredValue(function () {
			return $this->node_id;
		}, 'save');

		if ($cascadeSave) {
			$this->addCascadedSave($TVForum);
		}

		return $TVForum;
	}

	public function getNewTvForum($cascadeSave = true)
	{
		/** @var \XF\Entity\Forum $forum */
		$forum = $this->_em->create('XF:Forum');

		$forum->forum_type_id = 'trakt_tv';
		$forum->node_id = $this->_getDeferredValue(function () {
			return $this->node_id;
		}, 'save');

		if ($cascadeSave) {
			$this->addCascadedSave($forum);
		}

		return $forum;
	}
}
