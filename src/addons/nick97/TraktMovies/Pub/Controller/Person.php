<?php

namespace nick97\TraktMovies\Pub\Controller;


use nick97\TraktMovies\Trakt\Image;
use XF\Mvc\ParameterBag;

class Person extends \XF\Pub\Controller\AbstractController
{
	public function actionIndex()
	{
		return $this->notFound();
	}

	public function actionUpdate(ParameterBag $params)
	{
		$person = $this->assertPersonExists($params->person_id);
		if (!$person->canUpdate($error))
		{
			return $this->noPermission($error);
		}

		/** @var \nick97\TraktMovies\Service\Person\Editor $editor */
		$editor = $this->service('nick97\TraktMovies:Person\Editor', $person);

		/** @var \nick97\TraktMovies\Helper\Trakt\Api $apiHelper */
		$apiHelper = \XF::helper('nick97\TraktMovies:Trakt\Api');
		$traktClient = $apiHelper->getClient();

		$personData = $traktClient->getPeople()->getDetails($person->person_id);
		if ($traktClient->hasError())
		{
			return $this->error($traktClient->getError());
		}

		$editor->setFromApiResponse($personData);

		if (!$editor->validate($errors))
		{
			return $this->error($errors);
		}

		$imdbApi = new Image();

		$largeImageDate = $imdbApi->getImageLatestModifiedDate($person->profile_path);
		$largeImageDate = $largeImageDate !== null ? strtotime($largeImageDate) : 0;

		if ($largeImageDate > $person->large_image_date)
		{
			/** @var \nick97\TraktMovies\Service\Person\Image $imageService */
			$imageService = $this->app->service('nick97\TraktMovies:Person\Image', $person);

			$imageService->setImageFromApiPath($person->profile_path);
			$imageService->updateImage();
		}

		$editor->save();

		return $this->message(\XF::phrase('success'));
	}

	/**
	 * @param $id
	 * @param null $with
	 * @return \XF\Mvc\Entity\Entity|\nick97\TraktMovies\Entity\Person
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function assertPersonExists($id, $with = null)
	{
		return $this->assertRecordExists('nick97\TraktMovies:Person', $id, $with);
	}
}