<?php

namespace nick97\TraktTV\Service\Thread\TypeData;


use XF\Service\Thread\TypeData\SaverInterface;

class TvCreator extends \XF\Service\AbstractService implements SaverInterface
{
	use \XF\Service\ValidateAndSavableTrait;

	/**
	 * @var \nick97\TraktTV\XF\Entity\Thread
	 */
	protected $thread;

	/**
	 * @var \nick97\TraktTV\Service\TV\Creator
	 */
	protected $tvCreator;

	public function __construct(\XF\App $app, \XF\Entity\Thread $thread, $dummyId = null)
	{
		parent::__construct($app);
		$this->thread = $thread;
		$this->tvCreator = $this->service('nick97\TraktTV:TV\Creator', $thread, $dummyId);
		$this->tvCreator->setIsAutomated();
	}

	/**
	 * @return \nick97\TraktTV\XF\Entity\Thread
	 */
	public function getThread()
	{
		return $this->thread;
	}

	/**
	 * @return \nick97\TraktTV\Service\TV\Creator
	 */
	public function getTvCreator()
	{
		return $this->tvCreator;
	}

	protected function _validate()
	{
		$tvCreator = $this->tvCreator;
		$tvCreator->setComment($this->thread->getOption('tvOriginalMessage'));
		if (!$tvCreator->validate($errors)) {
			return $errors;
		}

		return [];
	}

	protected function _save()
	{
		/** @var \nick97\TraktTV\Entity\TV $tv */
		$tv = $this->tvCreator->save();

		if ($tv) {
			$firstPost = $this->thread->FirstPost;

			// Initially we do not have required post_id for poster
			$firstPost->message = $tv->getPostMessage();
			$firstPost->save();
		}

		$this->updateCoverImage($tv);
		return $tv;
	}

	/**
	 * @param \nick97\TraktTV\Entity\TV $tv
	 * @return void
	 * @throws \XF\PrintableException
	 */
	protected function updateCoverImage(\nick97\TraktTV\Entity\TV $tv)
	{
		if (!\XF::isAddOnActive('ThemeHouse/Covers')) {
			return;
		}

		$backdropSize = $this->app->options()->traktTvThreads_backdropCoverSize;
		if ($backdropSize == 'none') {
			return;
		}

		$thread = $this->thread;
		$tvData = $thread->getOption('tvData');
		if (empty($tvData['backdrop_path'])) {
			return;
		}

		/** @var \nick97\TraktTV\Service\Thread\Cover $coverService */
		$coverService = $this->app->service('nick97\TraktTV:Thread\Cover', $tv);
		$coverService->update($tvData['backdrop_path']);
	}
}
