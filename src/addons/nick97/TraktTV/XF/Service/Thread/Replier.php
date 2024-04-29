<?php

namespace nick97\TraktTV\XF\Service\Thread;

use XF\Entity\Thread;

class Replier extends XFCP_Replier
{
	/**
	 * @var \nick97\TraktTV\XF\Entity\Post
	 */
	protected $post;

	/**
	 * @var null|\nick97\TraktTV\Entity\TVPost;
	 */
	protected $tvEpisode = null;

	public function __construct(\XF\App $app, Thread $thread)
	{
		parent::__construct($app, $thread);

		/** @var \nick97\TraktTV\Entity\TVPost $tvEpisode */
		$this->tvEpisode = $this->post->getNewTvEpisode();
	}

	public function setTvSeasonEpisode($season, $episode)
	{
		$tv = $this->post->Thread->TV ?? null;
		if (!$tv) {
			throw new \LogicException('Cannot set episode without TV show');
		}

		$this->tvEpisode->tv_season = $season;
		$this->tvEpisode->tv_episode = $episode;

		/** @var \nick97\TraktTV\Helper\Trakt\Api $apiHelper */
		$apiHelper = \XF::helper('nick97\TraktTV:Trakt\Api');
		$traktClient = $apiHelper->getClient();

		$apiResponse = $traktClient->getTv($tv->tv_id)
			->getSeason($season)
			->getEpisode($episode)
			->getDetails(['credits']);

		if ($traktClient->hasError()) {
			$this->post->error($traktClient->getError());
			return;
		}

		$this->tvEpisode->setFromApiResponse($apiResponse);
		$this->tvEpisode->message = $this->post->message;

		$this->post->setAllowTvEpisodeEmptyMessage();
	}

	protected function _save()
	{
		if ($this->tvEpisode->tv_episode) {
			$this->post->addCascadedSave($this->tvEpisode);
		}

		return parent::_save();
	}
}
