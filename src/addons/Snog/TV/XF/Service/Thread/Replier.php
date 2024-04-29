<?php

namespace Snog\TV\XF\Service\Thread;

use XF\Entity\Thread;

class Replier extends XFCP_Replier
{
	/**
	 * @var \Snog\TV\XF\Entity\Post
	 */
	protected $post;

	/**
	 * @var null|\Snog\TV\Entity\TVPost;
	 */
	protected $tvEpisode = null;

	public function __construct(\XF\App $app, Thread $thread)
	{
		parent::__construct($app, $thread);

		/** @var \Snog\TV\Entity\TVPost $tvEpisode */
		$this->tvEpisode = $this->post->getNewTvEpisode();
	}

	public function setTvSeasonEpisode($season, $episode)
	{
		$tv = $this->post->Thread->TV ?? null;
		if (!$tv)
		{
			throw new \LogicException('Cannot set episode without TV show');
		}

		$this->tvEpisode->tv_season = $season;
		$this->tvEpisode->tv_episode = $episode;

		/** @var \Snog\TV\Helper\Tmdb\Api $apiHelper */
		$apiHelper = \XF::helper('Snog\TV:Tmdb\Api');
		$tmdbClient = $apiHelper->getClient();

		$apiResponse = $tmdbClient->getTv($tv->tv_id)
			->getSeason($season)
			->getEpisode($episode)
			->getDetails(['credits']);

		if ($tmdbClient->hasError())
		{
			$this->post->error($tmdbClient->getError());
			return;
		}

		$this->tvEpisode->setFromApiResponse($apiResponse);
		$this->tvEpisode->message = $this->post->message;

		$this->post->setAllowTvEpisodeEmptyMessage();
	}

	protected function _save()
	{
		if ($this->tvEpisode->tv_episode)
		{
			$this->post->addCascadedSave($this->tvEpisode);
		}

		return parent::_save();
	}
}
