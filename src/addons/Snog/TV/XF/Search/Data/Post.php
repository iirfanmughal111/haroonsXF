<?php

namespace Snog\TV\XF\Search\Data;

use XF\Mvc\Entity\Entity;

class Post extends XFCP_Post
{
	public function renderResult(Entity $entity, array $options = [])
	{
		/** @var \Snog\TV\XF\Entity\Post $entity */
		if ($entity->TVPost && \XF::options()->TvThreads_replaceSearchTemplate)
		{
			$data = $this->getTemplateData($entity, $options);
			return \XF::app()->templater()->renderTemplate('public:search_result_snog_tv_post', $data);
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