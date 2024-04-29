<?php

namespace Snog\TV\Service\Thread;

class Cover extends \XF\Service\AbstractService
{
	/**
	 * @var \Snog\TV\Entity\TV
	 */
	protected $tv;

	/**
	 * @var \ThemeHouse\Covers\Service\Cover\Image
	 */
	protected $coverImageService;

	/**
	 * @var bool
	 */
	private $isAutomated = false;

	public function __construct(\XF\App $app, \Snog\TV\Entity\TV $tv)
	{
		parent::__construct($app);

		$this->tv = $tv;
		$this->coverImageService = $this->setupCoverService();
	}

	protected function setupCoverService()
	{
		/** @var \ThemeHouse\Covers\Service\Cover\Image $coverImageService */
		$coverImageService = $this->app->service(
			'ThemeHouse\Covers:Cover\Image',
			$this->tv->thread_id,
			'thread'
		);

		return $coverImageService;
	}

	/**
	 * @param bool $isAutomated
	 */
	public function setIsAutomated(bool $isAutomated): void
	{
		$this->isAutomated = $isAutomated;
	}

	/**
	 * @param $backdropPath
	 * @return bool
	 * @throws \XF\PrintableException
	 */
	public function update($backdropPath)
	{
		$tv = $this->tv;
		$backdropSize = $this->app->options()->TvThreads_backdropCoverSize;
		if ($backdropSize == 'none')
		{
			return false;
		}

		if (!\XF::isAddOnActive('ThemeHouse/Covers'))
		{
			return false;
		}

		$coverImageService = $this->coverImageService;

		$tmdbApi = new \Snog\TV\Tmdb\Image();
		$backdropUrl = $tmdbApi->getImageUrl($backdropPath, $backdropSize);

		if ($coverImageService->downloadImage($backdropUrl) && $coverImageService->validateImageSet())
		{
			$coverDetails = $coverImageService->updateCoverImage();
			if ($coverDetails)
			{
				/** @var \ThemeHouse\Covers\Entity\Cover $cover */
				$cover = $this->app->em()->findOne('ThemeHouse\Covers:Cover', [
					'content_type' => 'thread',
					'content_id' => $tv->thread_id
				]);
				if (!$cover)
				{
					/** @var \ThemeHouse\Covers\Entity\Cover $cover */
					$cover = $this->app->em()->create('ThemeHouse\Covers:Cover');
					$cover->bulkSet([
						'content_type' => 'thread',
						'content_id' => $tv->thread_id
					]);
				}

				if ($this->isAutomated)
				{
					$cover->setOption('log_moderator', false);
				}

				/** @var \ThemeHouse\Covers\Service\Cover\Editor $coverEditorService */
				$coverEditorService = $this->app->service('ThemeHouse\Covers:Cover\Editor', $cover);
				$coverEditorService->setDefaults();
				$coverEditorService->setCoverDetails($coverDetails);

				if ($coverEditorService->validate())
				{
					$cover = $coverEditorService->save();
					if ($cover && $cover->cover_state != 'visible' && $cover->ApprovalQueue)
					{
						$cover->ApprovalQueue->delete();

						$cover->cover_state = 'visible';
						$cover->save();
					}
				}
			}
		}

		$tv->backdrop_path = $backdropPath;

		$tv->save();

		return true;
	}
}