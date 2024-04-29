<?php

namespace Snog\Movies\ThemeHouse\Covers\Pub\Controller;

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

		/** @var \Snog\Movies\XF\Entity\Thread $thread */
		$thread = $this->cover->Content;
		if (!($thread instanceof \XF\Entity\Thread))
		{
			return $this->notFound();
		}

		/** @var \Snog\Movies\Entity\Movie $movie */
		$movie = $thread->Movie;
		if (!$movie)
		{
			return $this->notFound();
		}

		/** @var \Snog\Movies\Helper\Tmdb\Api $apiHelper */
		$apiHelper = \XF::helper('Snog\Movies:Tmdb\Api');
		$tmdbClient = $apiHelper->getClient();

		$apiResponse = $tmdbClient->getMovie($movie->tmdb_id)->getDetails();

		if ($this->isPost())
		{
			if (empty($apiResponse['backdrop_path']))
			{
				return $this->notFound();
			}

			if ($this->filter('_xfWithData', 'bool'))
			{
				/** @var \Snog\Movies\Service\Thread\Cover $coverService */
				$coverService = $this->app->service('Snog\Movies:Thread\Cover', $movie);
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

		return $this->view('ThemeHouse\Covers:Cover\Image', 'snog_movies_cover_image', $viewParams);
	}
}