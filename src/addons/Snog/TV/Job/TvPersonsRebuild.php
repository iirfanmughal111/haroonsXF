<?php

namespace Snog\TV\Job;


class TvPersonsRebuild extends \XF\Job\AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT person_id
				FROM xf_snog_tv_person
				WHERE person_id > ?
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

		/** @var \Snog\TV\Helper\Tmdb\Api $apiHelper */
		$apiHelper = \XF::helper('Snog\TV:Tmdb\Api');
		$tmdbClient = $apiHelper->getClient();

		$personData = $tmdbClient->getPeople()->getDetails($id);
		if ($tmdbClient->hasError())
		{
			return;
		}

		$editor = $this->setupPersonEdit($person, $personData);
		if (!$editor->validate($errors))
		{
			return;
		}

		$editor->save();
		$this->finalizePersonEdit($editor);
	}

	protected function setupPersonEdit(\Snog\TV\Entity\Person $person, array $personData)
	{
		/** @var \Snog\TV\Service\Person\Editor $editor */
		$editor = $this->app->service('Snog\TV:Person\Editor', $person);
		$editor->setIsAutomated();
		$editor->setFromApiResponse($personData);

		return $editor;
	}

	protected function finalizePersonEdit(\Snog\TV\Service\Person\Editor $editor)
	{
		$person = $editor->getPerson();

		/** @var \Snog\TV\Service\Person\Image $imageService */
		$imageService = $this->app->service('Snog\TV:Person\Image', $person);
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
		return \XF::phrase('snog_tv_rebuild_persons');
	}
}