<?php

namespace Snog\TV\XF\Entity;

/**
* RELATIONS
* @property \Snog\TV\Entity\TVForum $TVForum
*/
class Node extends XFCP_Node
{
	public function getNewTv($cascadeSave = true)
	{
		/** @var \Snog\TV\Entity\TVForum $TVForum */
		$TVForum = $this->_em->create('Snog\TV:TVForum');

		$TVForum->node_id = $this->_getDeferredValue(function()
		{
			return $this->node_id;
		}, 'save');

		if ($cascadeSave)
		{
			$this->addCascadedSave($TVForum);
		}

		return $TVForum;
	}

	public function getNewTvForum($cascadeSave = true)
	{
		/** @var \XF\Entity\Forum $forum */
		$forum = $this->_em->create('XF:Forum');

		$forum->forum_type_id = 'snog_tv';
		$forum->node_id = $this->_getDeferredValue(function()
		{
			return $this->node_id;
		}, 'save');

		if ($cascadeSave)
		{
			$this->addCascadedSave($forum);
		}

		return $forum;
	}
}