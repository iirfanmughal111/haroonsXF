<?php


namespace Snog\Movies\Job;

class MoviePersonsRebuild extends \XF\Job\AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT person_id
				FROM xf_snog_movies_person
				WHERE person_id > ?
				ORDER BY person_id
			", $batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \Snog\Movies\Entity\Person $person */
		$person = $this->app->em()->find('Snog\Movies:Person', $id);
		if (!$person)
		{
			return;
		}

		/** @var \Snog\Movies\Helper\Tmdb\Api $apiHelper */
		$apiHelper = \XF::helper('Snog\Movies:Tmdb\Api');
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

	protected function setupPersonEdit(\Snog\Movies\Entity\Person $person, array $personData)
	{
		/** @var \Snog\Movies\Service\Person\Editor $editor */
		$editor = $this->app->service('Snog\Movies:Person\Editor', $person);
		$editor->setIsAutomated();
		$editor->setFromApiResponse($personData);

		return $editor;
	}

	protected function finalizePersonEdit(\Snog\Movies\Service\Person\Editor $editor)
	{
		$person = $editor->getPerson();

		/** @var \Snog\Movies\Service\Person\Image $imageService */
		$imageService = $this->app->service('Snog\Movies:Person\Image', $person);
		$imageService->setImageFromApiPath($person->profile_path);
		$imageService->updateImage();
	}

	public function canTriggerByChoice()
	{
		return true;
	}

	protected function getStatusType()
	{
		return \XF::phrase('snog_movies_rebuild_persons');
	}
}