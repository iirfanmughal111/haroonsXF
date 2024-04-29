<?php

namespace Snog\TV\Job;

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
		'tv' => [],
	];

	protected function setupData(array $data)
	{
		if (!isset($data['original_thread_id']))
		{
			throw new \LogicException('Thread ID must be provided');
		}

		if (!isset($data['tv']) || !is_array($data['tv']))
		{
			throw new \LogicException('TV data must be provided as array');
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
				WHERE forum_type_id = 'snog_tv'
					AND node_id > ?
				ORDER BY node_id
			", $batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \Snog\Movies\XF\Entity\Forum $genreForum */
		$genreForum = $this->app->em()->find('XF:Forum', $id);
		if (!$genreForum)
		{
			return;
		}

		$originalThread = $this->originalThread;
		$threadData = $originalThread->toArray(false);

		unset($threadData['thread_id'], $threadData['node_id']);

		$threadData['first_post_id'] = 0;
		$threadData['discussion_type'] = 'redirect';

		$tvData = $this->data['tv'];

		$checkGenres = $this->data['check_genres'];
		foreach ($checkGenres as $genre)
		{
			$this->checkGenre($genre, $genreForum, $tvData, $threadData);
		}
	}

	protected function checkGenre($genre, \Snog\Movies\XF\Entity\Forum $genreForum, $tvData, $threadData)
	{
		if ($genreForum->inMovieGenreAllowed($genre) && !in_array($genreForum->node_id, $this->linkedNodes))
		{
			$this->linkedNodes[] = $genreForum->node_id;

			$threadData['node_id'] = $genreForum->node_id;

			/** @var \XF\Entity\Thread $crossLink */
			$crossLink = $this->app->em()->create('XF:Thread');
			$crossLink->bulkSet($threadData);
			$crossLink->save();
			$crosslinkThreadId = $crossLink->getEntityId();

			/** @var \XF\Entity\ThreadRedirect $redirect */
			$redirect = $this->app->em()->create('XF:ThreadRedirect');
			$redirect->thread_id = $crosslinkThreadId;
			$redirect->target_url = $this->app->router('public')->buildLink('nopath:threads', $this->originalThread);
			$redirect->redirect_key = "thread-{$this->originalThread->thread_id}-{$this->originalThread->node_id}-";
			$redirect->expiry_date = 0;
			$redirect->save();

			/** @var \Snog\TV\Entity\TV $tv */
			$tv = $this->app->em()->create('Snog\Movies:Movie');

			$tv->setFromApiResponse($tvData);
			$tv->thread_id = $crosslinkThreadId;
			$tv->save(false, false);
		}
	}

	protected function getStatusType()
	{
		// TODO: Implement getStatusType() method.
	}
}