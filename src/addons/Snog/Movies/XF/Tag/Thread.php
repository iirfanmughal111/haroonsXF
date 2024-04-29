<?php

namespace Snog\Movies\XF\Tag;

use XF\Mvc\Entity\Entity;

class Thread extends XFCP_Thread
{
	public function renderResult(Entity $entity, array $options = [])
	{
		/** @var \Snog\Movies\XF\Entity\Thread $entity */
		if ($entity->Movie && \XF::options()->tmdbthreads_replaceSearchTemplate)
		{
			$data = $this->getTemplateData($entity, $options);
			return \XF::app()->templater()->renderTemplate('public:search_result_snog_movies_thread', $data);
		}

		return parent::renderResult($entity, $options);
	}
}