<?php

namespace Snog\TV\ThemeHouse\Covers\Pub\Controller;

use XF\Mvc\ParameterBag;

class Cover extends XFCP_Cover
{
	/**
	 * @throws \Exception
	 */
	public function actionTvUpdate(ParameterBag $params)
	{
		if (!$this->cover->canSetImage($error))
		{
			return $this->noPermission($error);
		}

		/** @var \Snog\TV\XF\Entity\Thread $thread */
		$thread = $this->cover->Content;
		if (!($thread instanceof \XF\Entity\Thread))
		{
			return $this->notFound();
		}

		/** @var \Snog\TV\Entity\TV $tv */
		$tv = $thread->TV;
		if (!$tv)
		{
			return $this->notFound();
		}

		/** @var \Snog\TV\Helper\Tmdb\Api $apiHelper */
		$apiHelper = \XF::helper('Snog\TV:Tmdb\Api');
		$tmdbClient = $apiHelper->getClient();

		$tvData = $tmdbClient->getTv($tv->tv_id)->getDetails();

		if ($this->isPost())
		{
			if (empty($tvData['backdrop_path']))
			{
				return $this->notFound();
			}

			if ($this->filter('_xfWithData', 'bool'))
			{
				/** @var \Snog\TV\Service\Thread\Cover $coverService */
				$coverService = $this->app->service('Snog\TV:Thread\Cover', $tv);
				$coverService->update($tvData['backdrop_path']);

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
			'hasNewBackdrop' => $tv->backdrop_path != $tvData['backdrop_path'],
			'backdropPath' => $tvData['backdrop_path'],
			'contentId' => $params['content_id'],
			'contentType' => $params['content_type'],
			'maxSize' => $this->getCoverRepo()->getCoverSizeMap()['m'],
		];

		return $this->view('ThemeHouse\Covers:Cover\Image', 'snog_tv_cover_image', $viewParams);
	}
}