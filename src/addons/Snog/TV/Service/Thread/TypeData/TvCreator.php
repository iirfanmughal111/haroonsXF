<?php

namespace Snog\TV\Service\Thread\TypeData;


use XF\Service\Thread\TypeData\SaverInterface;

class TvCreator extends \XF\Service\AbstractService implements SaverInterface
{
	use \XF\Service\ValidateAndSavableTrait;

	/**
	 * @var \Snog\TV\XF\Entity\Thread
	 */
	protected $thread;

	/**
	 * @var \Snog\TV\Service\TV\Creator
	 */
	protected $tvCreator;

	public function __construct(\XF\App $app, \XF\Entity\Thread $thread)
	{
		parent::__construct($app);
		$this->thread = $thread;
		$this->tvCreator = $this->service('Snog\TV:TV\Creator', $thread);
		$this->tvCreator->setIsAutomated();
	}

	/**
	 * @return \Snog\TV\XF\Entity\Thread
	 */
	public function getThread()
	{
		return $this->thread;
	}

	/**
	 * @return \Snog\TV\Service\TV\Creator
	 */
	public function getTvCreator()
	{
		return $this->tvCreator;
	}

	protected function _validate()
	{
		$tvCreator = $this->tvCreator;
		$tvCreator->setComment($this->thread->getOption('tvOriginalMessage'));
		if (!$tvCreator->validate($errors))
		{
			return $errors;
		}

		return [];
	}

	protected function _save()
	{
		/** @var \Snog\TV\Entity\TV $tv */
		$tv = $this->tvCreator->save();

		if ($tv)
		{
			$firstPost = $this->thread->FirstPost;

			// Initially we do not have required post_id for poster
			$firstPost->message = $tv->getPostMessage();
			$firstPost->save();
		}

		$this->updateCoverImage($tv);
		return $tv;
	}

	/**
	 * @param \Snog\TV\Entity\TV $tv
	 * @return void
	 * @throws \XF\PrintableException
	 */
	protected function updateCoverImage(\Snog\TV\Entity\TV $tv)
	{
		if (!\XF::isAddOnActive('ThemeHouse/Covers'))
		{
			return;
		}

		$backdropSize = $this->app->options()->TvThreads_backdropCoverSize;
		if ($backdropSize == 'none')
		{
			return;
		}

		$thread = $this->thread;
		$tvData = $thread->getOption('tvData');
		if (empty($tvData['backdrop_path']))
		{
			return;
		}

		/** @var \Snog\TV\Service\Thread\Cover $coverService */
		$coverService = $this->app->service('Snog\TV:Thread\Cover', $tv);
		$coverService->update($tvData['backdrop_path']);
	}
}