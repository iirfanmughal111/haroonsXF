<?php

namespace nick97\TraktMovies\ThemeHouse\Covers\Pub\Controller;

use XF\Mvc\ParameterBag;

class Cover extends XFCP_Cover
{
	/**
	 * @throws \Exception
	 */
	public function actionMovieUpdate(ParameterBag $params)
	{
		if (!$this->cover->canSetImage($error))
		{
			return $this->noPermission($error);
		}

		/** @var \nick97\TraktMovies\XF\Entity\Thread $thread */
		$thread = $this->cover->Content;
		if (!($thread instanceof \XF\Entity\Thread))
		{
			return $this->notFound();
		}

		/** @var \nick97\TraktMovies\Entity\Movie $movie */
		$movie = $thread->traktMovie;
		if (!$movie)
		{
			return $this->notFound();
		}

		/** @var \nick97\TraktMovies\Helper\Trakt\Api $apiHelper */
		$apiHelper = \XF::helper('nick97\TraktMovies:Trakt\Api');
		$traktClient = $apiHelper->getClient();

		$apiResponse = $traktClient->getMovie($movie->trakt_id)->getDetails();

		if ($this->isPost())
		{
			if (empty($apiResponse['backdrop_path']))
			{
				return $this->notFound();
			}

			if ($this->filter('_xfWithData', 'bool'))
			{
				/** @var \nick97\TraktMovies\Service\Thread\Cover $coverService */
				$coverService = $this->app->service('nick97\TraktMovies:Thread\Cover', $movie);
				$coverService->update($apiResponse['backdrop_path']);

				$visitor = \XF::visitor();

				$reply = $this->redirect(
					$this->coverHandler->getContentUrl(false, '', ['th_coversInit' => 1]),
					\XF::phrase('upload_completed_successfully')
				);

				$reply->setJsonParams([
					'userId' => $visitor->user_id,
					'contentId' => $params['content_id'],
					'contentType' => $params['content_type'],
					'defaultCovers' => ($visitor->getAvatarUrl('s') === null),
				]);

				return $reply;
			}
			else
			{
				return $this->redirect($this->coverHandler->getContentUrl(false, '', ['th_coversInit' => 1]));
			}
		}

		$viewParams = [
			'cover' => $this->cover,
			'hasNewBackdrop' => $movie->backdrop_path != $apiResponse['backdrop_path'],
			'backdropPath' => $apiResponse['backdrop_path'],
			'contentId' => $params['content_id'],
			'contentType' => $params['content_type'],
			'maxSize' => $this->getCoverRepo()->getCoverSizeMap()['m'],
		];

		return $this->view('ThemeHouse\Covers:Cover\Image', 'trakt_movies_cover_image', $viewParams);
	}
}