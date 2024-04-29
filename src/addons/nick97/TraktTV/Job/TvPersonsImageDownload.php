<?php

namespace nick97\TraktTV\Job;


class TvPersonsImageDownload extends \XF\Job\AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT person_id
				FROM nick97_trakt_tv_person
				WHERE person_id > ? AND profile_path != ''
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

		/** @var \nick97\TraktTV\Service\Person\Image $imageService */
		$imageService = $this->app->service('nick97\TraktTV:Person\Image', $person);
		$imageService->deleteImageFiles();

		$imageService->setImageFromApiPath($person->profile_path, 'w300_and_h450_bestv2');
		if (!$imageService->updateImage()) {
			$person->profile_path = '';
		}

		$person->saveIfChanged();
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
