<?php

namespace nick97\TraktTV\Job;


class TvPersonsRebuild extends \XF\Job\AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT person_id
				FROM nick97_trakt_tv_person
				WHERE person_id > ?
				ORDER BY person_id
			",
			$batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \nick97\TraktTV\Entity\Person $person */
		$person = $this->app->em()->find('nick97\TraktTV:Person', $id);
		if (!$person) {
			return;
		}

		/** @var \nick97\TraktTV\Helper\Trakt\Api $apiHelper */
		$apiHelper = \XF::helper('nick97\TraktTV:Trakt\Api');
		$traktClient = $apiHelper->getClient();

		$personData = $traktClient->getPeople()->getDetails($id);
		if ($traktClient->hasError()) {
			return;
		}

		$editor = $this->setupPersonEdit($person, $personData);
		if (!$editor->validate($errors)) {
			return;
		}

		$editor->save();
		$this->finalizePersonEdit($editor);
	}

	protected function setupPersonEdit(\nick97\TraktTV\Entity\Person $person, array $personData)
	{
		/** @var \nick97\TraktTV\Service\Person\Editor $editor */
		$editor = $this->app->service('nick97\TraktTV:Person\Editor', $person);
		$editor->setIsAutomated();
		$editor->setFromApiResponse($personData);

		return $editor;
	}

	protected function finalizePersonEdit(\nick97\TraktTV\Service\Person\Editor $editor)
	{
		$person = $editor->getPerson();

		/** @var \nick97\TraktTV\Service\Person\Image $imageService */
		$imageService = $this->app->service('nick97\TraktTV:Person\Image', $person);
		$imageService->deleteImageFiles();

		$imageService->setImageFromApiPath($person->profile_path, 'w300_and_h450_bestv2');
		$imageService->updateImage();
	}

	public function canTriggerByChoice()
	{
		return true;
	}

	protected function getStatusType()
	{
		return \XF::phrase('trakt_tv_rebuild_persons');
	}
}
