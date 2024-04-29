<?php

namespace Snog\TV\Job;


class TvPersonsImageDownload extends \XF\Job\AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT person_id
				FROM xf_snog_tv_person
				WHERE person_id > ? AND profile_path != ''
				ORDER BY person_id
			", $batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \Snog\TV\Entity\Person $person */
		$person = $this->app->em()->find('Snog\TV:Person', $id);
		if (!$person)
		{
			return;
		}

		/** @var \Snog\TV\Service\Person\Image $imageService */
		$imageService = $this->app->service('Snog\TV:Person\Image', $person);
		$imageService->deleteImageFiles();

		$imageService->setImageFromApiPath($person->profile_path, 'w300_and_h450_bestv2');
		if (!$imageService->updateImage())
		{
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
		return \XF::phrase('snog_tv_rebuild_persons');
	}
}