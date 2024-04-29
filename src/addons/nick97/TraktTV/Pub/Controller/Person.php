<?php

namespace nick97\TraktTV\Pub\Controller;


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
		if (!$person->canUpdate($error)) {
			return $this->noPermission($error);
		}

		/** @var \nick97\TraktTV\Helper\Trakt\Api $apiHelper */
		$apiHelper = \XF::helper('nick97\TraktTV:Trakt\Api');
		$traktClient = $apiHelper->getClient();

		$personData = $traktClient->getPeople()->getDetails($person->person_id);
		if ($traktClient->hasError()) {
			return $this->error($traktClient->getError());
		}

		$editor = $this->setupPersonEdit($person, $personData);

		if (!$editor->validate($errors)) {
			return $this->error($errors);
		}
		$editor->save();
		$this->finalizePersonEdit($editor, $personData);

		return $this->message(\XF::phrase('success'));
	}

	protected function setupPersonEdit(\nick97\TraktTV\Entity\Person $person, $personData)
	{
		/** @var \nick97\TraktTV\Service\Person\Editor $editor */
		$editor = $this->service('nick97\TraktTV:Person\Editor', $person);
		$editor->setFromApiResponse($personData);

		return $editor;
	}

	protected function finalizePersonEdit(\nick97\TraktTV\Service\Person\Editor $editor, $personData)
	{
		$imdbApi = new \nick97\TraktTV\Trakt\Image();

		$person = $editor->getPerson();
		$profilePath = $person->profile_path;

		$largeImageDate = $imdbApi->getImageLatestModifiedDate($profilePath);
		$largeImageDate = $largeImageDate !== null ? strtotime($largeImageDate) : 0;

		if ($person->large_image_date < $largeImageDate) {
			/** @var \nick97\TraktTV\Service\Person\Image $imageService */
			$imageService = $this->app->service('nick97\TraktTV:Person\Image', $person);
			$imageService->deleteImageFiles();

			$imageService->setImageFromApiPath($profilePath, 'w300_and_h450_bestv2');
			$imageService->updateImage();
		}
	}

	/**
	 * @param $id
	 * @param null $with
	 * @return \XF\Mvc\Entity\Entity|\nick97\TraktTV\Entity\Person
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function assertPersonExists($id, $with = null)
	{
		return $this->assertRecordExists('nick97\TraktTV:Person', $id, $with);
	}
}
