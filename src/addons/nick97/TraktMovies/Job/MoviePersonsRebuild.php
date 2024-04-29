<?php


namespace nick97\TraktMovies\Job;

class MoviePersonsRebuild extends \XF\Job\AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT person_id
				FROM nick97_trakt_movies_person
				WHERE person_id > ?
				ORDER BY person_id
			", $batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \nick97\TraktMovies\Entity\Person $person */
		$person = $this->app->em()->find('nick97\TraktMovies:Person', $id);
		if (!$person)
		{
			return;
		}

		/** @var \nick97\TraktMovies\Helper\Trakt\Api $apiHelper */
		$apiHelper = \XF::helper('nick97\TraktMovies:Trakt\Api');
		$traktClient = $apiHelper->getClient();

		$personData = $traktClient->getPeople()->getDetails($id);
		if ($traktClient->hasError())
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

	protected function setupPersonEdit(\nick97\TraktMovies\Entity\Person $person, array $personData)
	{
		/** @var \nick97\TraktMovies\Service\Person\Editor $editor */
		$editor = $this->app->service('nick97\TraktMovies:Person\Editor', $person);
		$editor->setIsAutomated();
		$editor->setFromApiResponse($personData);

		return $editor;
	}

	protected function finalizePersonEdit(\nick97\TraktMovies\Service\Person\Editor $editor)
	{
		$person = $editor->getPerson();

		/** @var \nick97\TraktMovies\Service\Person\Image $imageService */
		$imageService = $this->app->service('nick97\TraktMovies:Person\Image', $person);
		$imageService->setImageFromApiPath($person->profile_path);
		$imageService->updateImage();
	}

	public function canTriggerByChoice()
	{
		return true;
	}

	protected function getStatusType()
	{
		return \XF::phrase('trakt_movies_rebuild_persons');
	}
}