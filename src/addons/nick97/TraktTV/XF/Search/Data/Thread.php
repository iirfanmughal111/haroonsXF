<?php

namespace nick97\TraktTV\XF\Search\Data;

use XF\Mvc\Entity\Entity;

class Thread extends XFCP_Thread
{
	public function renderResult(Entity $entity, array $options = [])
	{
		/** @var \nick97\TraktTV\XF\Entity\Thread $entity */
		if ($entity->traktTV && \XF::options()->traktTvThreads_replaceSearchTemplate) {
			$data = $this->getTemplateData($entity, $options);
			return \XF::app()->templater()->renderTemplate('public:search_result_trakt_tv_thread', $data);
		}

		return parent::renderResult($entity, $options);
	}

	public function getEntityWith($forView = false)
	{
		$with = parent::getEntityWith($forView);
		$with[] = 'TV';

		return $with;
	}
}
