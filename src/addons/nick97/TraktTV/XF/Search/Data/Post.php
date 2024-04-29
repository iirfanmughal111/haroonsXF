<?php

namespace nick97\TraktTV\XF\Search\Data;

use XF\Mvc\Entity\Entity;

class Post extends XFCP_Post
{
	public function renderResult(Entity $entity, array $options = [])
	{
		/** @var \nick97\TraktTV\XF\Entity\Post $entity */
		if ($entity->TVPost && \XF::options()->traktTvThreads_replaceSearchTemplate) {
			$data = $this->getTemplateData($entity, $options);
			return \XF::app()->templater()->renderTemplate('public:search_result_trakt_tv_post', $data);
		}

		return parent::renderResult($entity, $options);
	}

	public function getEntityWith($forView = false)
	{
		$with = parent::getEntityWith($forView);
		$with[] = 'TVPost';

		return $with;
	}
}
