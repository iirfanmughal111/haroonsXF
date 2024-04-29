<?php

namespace Snog\Movies\Pub\Controller;


use Snog\Movies\Tmdb\Image;
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

		/** @var \Snog\Movies\Service\Person\Editor $editor */
		$editor = $this->service('Snog\Movies:Person\Editor', $person);

		/** @var \Snog\Movies\Helper\Tmdb\Api $apiHelper */
		$apiHelper = \XF::helper('Snog\Movies:Tmdb\Api');
		$tmdbClient = $apiHelper->getClient();

		$personData = $tmdbClient->getPeople()->getDetails($person->person_id);
		if ($tmdbClient->hasError())
		{
			return $this->error($tmdbClient->getError());
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
			/** @var \Snog\Movies\Service\Person\Image $imageService */
			$imageService = $this->app->service('Snog\Movies:Person\Image', $person);

			$imageService->setImageFromApiPath($person->profile_path);
			$imageService->updateImage();
		}

		$editor->save();

		return $this->message(\XF::phrase('success'));
	}

	/**
	 * @param $id
	 * @param null $with
	 * @return \XF\Mvc\Entity\Entity|\Snog\Movies\Entity\Person
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function assertPersonExists($id, $with = null)
	{
		return $this->assertRecordExists('Snog\Movies:Person', $id, $with);
	}
}