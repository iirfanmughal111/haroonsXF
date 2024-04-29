<?php

namespace Snog\TV\Pub\Controller;


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

		/** @var \Snog\TV\Helper\Tmdb\Api $apiHelper */
		$apiHelper = \XF::helper('Snog\TV:Tmdb\Api');
		$tmdbClient = $apiHelper->getClient();

		$personData = $tmdbClient->getPeople()->getDetails($person->person_id);
		if ($tmdbClient->hasError())
		{
			return $this->error($tmdbClient->getError());
		}

		$editor = $this->setupPersonEdit($person, $personData);

		if (!$editor->validate($errors))
		{
			return $this->error($errors);
		}
		$editor->save();
		$this->finalizePersonEdit($editor, $personData);

		return $this->message(\XF::phrase('success'));
	}

	protected function setupPersonEdit(\Snog\TV\Entity\Person $person, $personData)
	{
		/** @var \Snog\TV\Service\Person\Editor $editor */
		$editor = $this->service('Snog\TV:Person\Editor', $person);
		$editor->setFromApiResponse($personData);

		return $editor;
	}

	protected function finalizePersonEdit(\Snog\TV\Service\Person\Editor $editor, $personData)
	{
		$imdbApi = new \Snog\TV\Tmdb\Image();

		$person = $editor->getPerson();
		$profilePath = $person->profile_path;

		$largeImageDate = $imdbApi->getImageLatestModifiedDate($profilePath);
		$largeImageDate = $largeImageDate !== null ? strtotime($largeImageDate) : 0;

		if ($person->large_image_date < $largeImageDate)
		{
			/** @var \Snog\TV\Service\Person\Image $imageService */
			$imageService = $this->app->service('Snog\TV:Person\Image', $person);
			$imageService->deleteImageFiles();

			$imageService->setImageFromApiPath($profilePath, 'w300_and_h450_bestv2');
			$imageService->updateImage();
		}
	}

	/**
	 * @param $id
	 * @param null $with
	 * @return \XF\Mvc\Entity\Entity|\Snog\TV\Entity\Person
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function assertPersonExists($id, $with = null)
	{
		return $this->assertRecordExists('Snog\TV:Person', $id, $with);
	}
}