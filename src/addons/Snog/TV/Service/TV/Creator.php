<?php

namespace Snog\TV\Service\TV;


class Creator extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/**
	 * @var \XF\Entity\Thread
	 */
	protected $thread;

	protected $tvData;

	/**
	 * @var \Snog\TV\Entity\TV
	 */
	protected $tv;

	/**
	 * @var Preparer
	 */
	protected $tvPreparer;

	protected $performValidations = true;

	public function __construct(\XF\App $app, \XF\Entity\Thread $thread)
	{
		parent::__construct($app);
		$this->setThread($thread);

		$this->tvPreparer = $this->service('Snog\TV:TV\Preparer', $this->tv);
	}

	protected function setThread(\XF\Entity\Thread $thread)
	{
		$this->thread = $thread;

		/** @var \Snog\TV\Entity\TV $tv */
		$tv = $this->em()->create('Snog\TV:TV');
		$threadId = $thread->thread_id;
		if (!$threadId)
		{
			$threadId = $tv->em()->getDeferredValue(function () use ($thread) {
				return $thread->thread_id;
			}, 'save');
		}

		$tv->thread_id = $threadId;

		$this->tvData = $this->thread->getOption('tvData');

		$this->tv = $tv;
	}

	public function getThread()
	{
		return $this->thread;
	}

	public function getTv()
	{
		return $this->tv;
	}

	public function getTvData()
	{
		return $this->tvData;
	}

	public function setTvId(int $id)
	{
		if (!$id)
		{
			return;
		}

		$this->tv->tv_id = $id;

		if (!$this->tvData)
		{
			/** @var \Snog\TV\Helper\Tmdb\Api $apiHelper */
			$apiHelper = \XF::helper('Snog\TV:Tmdb\Api');
			$tmdbClient = $apiHelper->getClient();

			/** @var \Snog\TV\Repository\TV $tvRepo */
			$tvRepo = $this->repository('Snog\TV:TV');
			$subRequests = $tvRepo->getSubRequestForFullApiRequest('create');

			$tvData = $tmdbClient->getTv($id)->getDetails($subRequests);
			if ($tmdbClient->hasError())
			{
				$this->validationErrors += [$tmdbClient->getError()];
			}

			$this->tvData = $tvData;
		}

		$this->setTvData($this->tvData);
	}

	public function setComment($comment)
	{
		$this->tv->comment = $comment;
	}

	public function setTvData(array $data)
	{
		$this->tv->setFromApiResponse($data);
	}

	public function setPerformValidations($perform)
	{
		$this->performValidations = (bool) $perform;
	}

	public function setIsAutomated()
	{
		$this->setPerformValidations(false);
	}

	protected function _validate()
	{
		$tv = $this->tv;
		$tv->preSave();
		$errors = $tv->getErrors();

		if ($this->performValidations)
		{
		}

		return $errors;
	}

	protected function getBlockingJobs()
	{
		$options = $this->app->options();
		$jobList = [];

		$apiResponse = $this->tvData;

		if ($options->TvThreads_fetchCredits)
		{
			$creditsData = [];
			if (isset($apiResponse['aggregate_credits']))
			{
				$creditsData = $apiResponse['aggregate_credits'];
			}
			elseif (isset($apiResponse['credits']))
			{
				$creditsData = $apiResponse['credits'];
			}

			if ($creditsData)
			{
				$casts = $creditsData['cast'] ?? [];
				$crews = $creditsData['crew'] ?? [];

				$ungroupedCredits = array_merge($casts, $crews);

				$jobList[] = [
					'Snog\TV:TvNewPersons', [
						'newPersons' => $ungroupedCredits
					]
				];
			}
		}

		if ($options->TvThreads_fetchCompanies && !empty($apiResponse['production_companies']))
		{
			$jobList[] = [
				'Snog\TV:TvNewCompanies', [
					'companyIds' => array_column($apiResponse['production_companies'], 'id')
				]
			];
		}

		if (!empty($apiResponse['networks']))
		{
			$jobList[] = [
				'Snog\TV:TvNewNetworks', [
					'networkIds' => array_column($apiResponse['networks'], 'id')
				]
			];
		}

		return $jobList;
	}

	protected function runBlockingJobs()
	{
		$jobList = $this->getBlockingJobs();

		if ($jobList)
		{
			$this->app->jobManager()->enqueueAutoBlocking('XF:Atomic', [
				'execute' => $jobList
			]);
		}
	}

	protected function getFinalJobs()
	{
		$jobList = [];

		$tv = $this->tv;
		$apiResponse = $this->tvData;
		$options = $this->app->options();

		if ($options->TvThreads_use_genres && $options->TvThreads_crosslink)
		{
			$jobList[] = [
				'Snog\TV:CrossLinkCreate',
				[
					'check_genres' => explode(',', $tv->tv_genres),
					'original_thread_id' => $this->thread->thread_id,
					'tv' => $apiResponse
				]
			];
		}

		return $jobList;
	}

	protected function runFinalJobs()
	{
		$tv = $this->tv;

		$jobList = $this->getFinalJobs();

		if ($jobList)
		{
			$this->app->jobManager()->enqueueUnique('snogTvInsert' . $tv->thread_id, 'XF:Atomic', [
				'execute' => $jobList
			], false);
		}
	}

	protected function _save()
	{
		$app = $this->app;
		$tv = $this->tv;
		$thread = $tv->Thread;
		$options = $app->options();

		$db = $this->db();
		$db->beginTransaction();

		$tv->save();

		$this->tvPreparer->afterInsert();

		if (isset($thread->User) && $thread->User->user_id != \XF::visitor()->user_id)
		{
			$app->logger()->logModeratorAction('thread', $thread, 'snog_tv_tv_create');
		}

		$tvData = $this->tvData;

		if ($app->options()->TvThreads_force_comments)
		{
			$comment = $thread->getOption('tvOriginalMessage');
			if ($comment)
			{
				/** @var \XF\Service\Thread\Replier $replier */
				$replier = \XF::service('XF:Thread\Replier', $thread);
				$replier->setMessage($comment);
				if ($thread->Forum->canUploadAndManageAttachments())
				{
					$replier->setAttachmentHash(\XF::app()->request()->filter('attachment_hash', 'str'));
				}
				$replier->save();
			}
		}

		$this->runBlockingJobs();

		/** @var \Snog\TV\Repository\TV $tvRepo */
		$tvRepo = $this->repository('Snog\TV:TV');

		if ($options->TvThreads_fetchCredits)
		{
			$creditsData = [];
			if (isset($tvData['aggregate_credits']))
			{
				$creditsData = $tvData['aggregate_credits'];
			}
			elseif (isset($tvData['credits']))
			{
				$creditsData = $tvData['credits'];
			}

			if ($creditsData)
			{
				$tvRepo->insertOrUpdateShowCredits($tv->tv_id, 0, 0, $creditsData);
			}
		}

		if ($options->TvThreads_fetchVideos && isset($tvData['videos']['results']))
		{
			$tvRepo->insertOrUpdateShowVideos($tv->tv_id, 0, 0, $tvData['videos']['results']);
		}

		if ($this->app->options()->TvThreads_useLocalImages && $tv->tv_image)
		{
			/** @var Image $imageService */
			$imageService = \XF::service('Snog\TV:TV\Image', $tv);
			$imageService->setImageFromApiPath($tv->tv_image, $this->app->options()->TvThreads_largePosterSize);
			$imageService->updateImage();
		}

		$db->commit();

		$this->runFinalJobs();

		return $tv;
	}
}