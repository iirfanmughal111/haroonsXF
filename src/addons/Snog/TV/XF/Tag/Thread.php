<?php

namespace Snog\TV\XF\Tag;

use XF\Mvc\Entity\Entity;

class Thread extends XFCP_Thread
{
	public function renderResult(Entity $entity, array $options = [])
	{
		/** @var \Snog\TV\XF\Entity\Thread $entity */
		if ($entity->TV && \XF::options()->TvThreads_replaceSearchTemplate)
		{
			$data = $this->getTemplateData($entity, $options);
			return \XF::app()->templater()->renderTemplate('public:search_result_snog_tv_thread', $data);
		}

		return parent::renderResult($entity, $options);
	}
}