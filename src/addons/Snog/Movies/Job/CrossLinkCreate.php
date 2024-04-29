<?php


namespace Snog\Movies\Job;


class CrossLinkCreate extends \XF\Job\AbstractRebuildJob
{
	/**
	 * @var \XF\Entity\Thread
	 */
	protected $originalThread;

	protected $linkedNodes = [];

	protected $defaultData = [
		'check_genres' => [],
		'original_thread_id' => 0,
		'movieApiResponse' => [],
	];

	protected function setupData(array $data)
	{
		if (!isset($data['original_thread_id']))
		{
			throw new \LogicException('Thread ID must be provided');
		}

		if (!isset($data['movieApiResponse']) || !is_array($data['movieApiResponse']))
		{
			throw new \LogicException('Movie data must be provided as array');
		}

		/** @var \XF\Entity\Thread $originalThread */
		$originalThread = $this->app->em()->find('XF:Thread', $data['original_thread_id']);
		if (!$originalThread)
		{
			throw new \LogicException('Thread not found');
		}

		$this->originalThread = $originalThread;
		$this->linkedNodes[] = $originalThread->node_id;

		return parent::setupData($data);
	}

	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT node_id
				FROM xf_forum
				WHERE forum_type_id = 'snog_movies_movie'
					AND node_id > ?
				ORDER BY node_id
			", $batch
		), $start);
	}

	protected function rebuildById($id)
	{
		$app = $this->app;
		$em = $app->em();

		/** @var \Snog\Movies\XF\Entity\Forum $genreForum */
		$genreForum = $em->find('XF:Forum', $id);
		if (!$genreForum)
		{
			return;
		}

		$originalThread = $this->originalThread;
		$threadData = $originalThread->toArray(false);

		unset($threadData['thread_id'], $threadData['node_id']);

		$threadData['first_post_id'] = 0;
		$threadData['discussion_type'] = 'redirect';

		$apiResponse = $this->data['movieApiResponse'];

		$checkGenres = $this->data['check_genres'];
		foreach ($checkGenres as $genre)
		{
			if ($genreForum->inMovieGenreAllowed($genre) && !in_array($genreForum->node_id, $this->linkedNodes))
			{
				$this->linkedNodes[] = $genreForum->node_id;

				$threadData['node_id'] = $genreForum->node_id;

				/** @var \XF\Entity\Thread $crossLink */
				$crossLink = $em->create('XF:Thread');
				$crossLink->bulkSet($threadData);
				$crossLink->save();
				$crosslinkThreadId = $crossLink->getEntityId();

				/** @var \XF\Entity\ThreadRedirect $redirect */
				$redirect = $em->create('XF:ThreadRedirect');
				$redirect->thread_id = $crosslinkThreadId;
				$redirect->target_url = $app->router('public')->buildLink('nopath:threads', $originalThread);
				$redirect->redirect_key = "thread-{$originalThread->thread_id}-{$originalThread->node_id}-";
				$redirect->expiry_date = 0;
				$redirect->save();

				/** @var \Snog\Movies\Entity\Movie $movie */
				$movie = $em->create('Snog\Movies:Movie');
				$movie->setFromApiResponse($apiResponse);
				$movie->thread_id = $crosslinkThreadId;
				$movie->save(false, false);
			}
		}
	}

	protected function getStatusType()
	{
		// TODO: Implement getStatusType() method.
	}
}