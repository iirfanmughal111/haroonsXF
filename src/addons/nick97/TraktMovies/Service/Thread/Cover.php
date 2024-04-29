<?php

namespace nick97\TraktMovies\Service\Thread;

class Cover extends \XF\Service\AbstractService
{
	/**
	 * @var \nick97\TraktMovies\Entity\Movie
	 */
	protected $movie;

	/**
	 * @var \ThemeHouse\Covers\Service\Cover\Image
	 */
	protected $coverImageService;

	protected $isAutomated = false;

	public function __construct(\XF\App $app, \nick97\TraktMovies\Entity\Movie $movie)
	{
		parent::__construct($app);

		$this->movie = $movie;
		$this->coverImageService = $this->setupCoverService();
	}

	protected function setupCoverService()
	{
		/** @var \ThemeHouse\Covers\Service\Cover\Image $coverImageService */
		$coverImageService = $this->app->service(
			'ThemeHouse\Covers:Cover\Image',
			$this->movie->thread_id,
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
		$movie = $this->movie;
		$backdropSize = $this->app->options()->traktthreads_backdropCoverSize;
		if ($backdropSize == 'none') {
			return false;
		}

		if (!\XF::isAddOnActive('ThemeHouse/Covers')) {
			return false;
		}

		$coverImageService = $this->coverImageService;

		$traktApi = new \Snog\TV\Trakt\Image();
		$backdropUrl = $traktApi->getImageUrl($backdropPath, $backdropSize);

		if ($coverImageService->downloadImage($backdropUrl) && $coverImageService->validateImageSet()) {
			$coverDetails = $coverImageService->updateCoverImage();
			if ($coverDetails) {
				/** @var \ThemeHouse\Covers\Entity\Cover $cover */
				$cover = $this->app->em()->findOne('ThemeHouse\Covers:Cover', [
					'content_type' => 'thread',
					'content_id' => $movie->thread_id
				]);
				if (!$cover) {
					/** @var \ThemeHouse\Covers\Entity\Cover $cover */
					$cover = $this->app->em()->create('ThemeHouse\Covers:Cover');
					$cover->bulkSet([
						'content_type' => 'thread',
						'content_id' => $movie->thread_id
					]);
				}

				if ($this->isAutomated) {
					$cover->setOption('log_moderator', false);
				}

				/** @var \ThemeHouse\Covers\Service\Cover\Editor $coverEditorService */
				$coverEditorService = $this->app->service('ThemeHouse\Covers:Cover\Editor', $cover);
				$coverEditorService->setDefaults();
				$coverEditorService->setCoverDetails($coverDetails);

				if ($coverEditorService->validate()) {
					$cover = $coverEditorService->save();
					if ($cover && $cover->cover_state != 'visible' && $cover->ApprovalQueue) {
						$cover->ApprovalQueue->delete();

						$cover->cover_state = 'visible';
						$cover->save();
					}
				}
			}
		}

		$movie->backdrop_path = $backdropPath;

		$movie->save();

		return true;
	}
}
