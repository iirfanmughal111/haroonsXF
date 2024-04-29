<?php

namespace nick97\TraktMovies\XF\Tag;

use XF\Mvc\Entity\Entity;

class Thread extends XFCP_Thread
{
	public function renderResult(Entity $entity, array $options = [])
	{
		/** @var \nick97\TraktMovies\XF\Entity\Thread $entity */
		if ($entity->traktMovie && \XF::options()->traktthreads_replaceSearchTemplate) {
			$data = $this->getTemplateData($entity, $options);
			return \XF::app()->templater()->renderTemplate('public:search_result_trakt_movies_thread', $data);
		}

		return parent::renderResult($entity, $options);
	}
}
