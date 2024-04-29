<?php

namespace nick97\TraktTV\ThemeHouse\Covers\Pub\Controller;

use XF\Mvc\ParameterBag;

class Cover extends XFCP_Cover
{
	/**
	 * @throws \Exception
	 */
	public function actionTvUpdate(ParameterBag $params)
	{
		if (!$this->cover->canSetImage($error)) {
			return $this->noPermission($error);
		}

		/** @var \nick97\TraktTV\XF\Entity\Thread $thread */
		$thread = $this->cover->Content;
		if (!($thread instanceof \XF\Entity\Thread)) {
			return $this->notFound();
		}

		/** @var \nick97\TraktTV\Entity\TV $tv */
		$tv = $thread->traktTV;
		if (!$tv) {
			return $this->notFound();
		}

		/** @var \nick97\TraktTV\Helper\Trakt\Api $apiHelper */
		$apiHelper = \XF::helper('nick97\TraktTV:Trakt\Api');
		$traktClient = $apiHelper->getClient();

		$tvData = $traktClient->getTv($tv->tv_id)->getDetails();

		if ($this->isPost()) {
			if (empty($tvData['backdrop_path'])) {
				return $this->notFound();
			}

			if ($this->filter('_xfWithData', 'bool')) {
				/** @var \nick97\TraktTV\Service\Thread\Cover $coverService */
				$coverService = $this->app->service('nick97\TraktTV:Thread\Cover', $tv);
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
			} else {
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

		return $this->view('ThemeHouse\Covers:Cover\Image', 'trakt_tv_cover_image', $viewParams);
	}
}
