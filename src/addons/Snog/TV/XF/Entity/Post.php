<?php

namespace Snog\TV\XF\Entity;

/**
 * RELATIONS
 * @property Thread $Thread
 * @property \Snog\TV\Entity\TVPost $TVPost
 */
class Post extends XFCP_Post
{
	public function getNewTvEpisode()
	{
		/** @var \Snog\TV\Entity\TVPost $tvPost */
		$tvPost = $this->getRelationOrDefault('TVPost', false);

		$tvPost->post_id = $this->_getDeferredValue(function()
		{
			return $this->post_id;
		}, 'save');

		return $tvPost;
	}

	public function setAllowTvEpisodeEmptyMessage()
	{
		$this->_structure->columns['message']['required'] = false;
	}

	protected function _preSave()
	{
		parent::_preSave();

		if ($this->TVPost && empty($this->message) && $this->hasErrors())
		{
			foreach ($this->_errors as $key => $error)
			{
				if ($error instanceof \XF\Phrase && $error->getName() == 'please_enter_valid_message')
				{
					unset($this->_errors[$key]);
				}
			}
		}
	}
}