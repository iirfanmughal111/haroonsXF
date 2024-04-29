<?php

namespace nick97\TraktTV\Service\Person;

use XF\Util\File;

class Preparer extends \XF\Service\AbstractService
{
	/**
	 * @var \nick97\TraktTV\Entity\Person
	 */
	protected $person;

	public function __construct(\XF\App $app, \nick97\TraktTV\Entity\Person $person)
	{
		parent::__construct($app);
		$this->person = $person;
	}

	public function getPerson()
	{
		return $this->person;
	}

	public function saveImage($srcPath, $size, $localPath, $tempPath)
	{
		$traktApi = new \nick97\TraktTV\Trakt\Image();
		$poster = $traktApi->getImage($srcPath, $size, $lastModified);
		if ($traktApi->hasError()) {
			return 0;
		}

		if (file_exists($tempPath)) {
			unlink($tempPath);
		}
		file_put_contents($tempPath, $poster);
		File::copyFileToAbstractedPath($tempPath, $localPath);
		unlink($tempPath);

		return $lastModified !== null ? strtotime($lastModified) : 0;
	}

	public function afterInsert()
	{
		$person = $this->person;

		if (!empty($person->profile_path)) {
			$profilePath = $person->profile_path;
			$tempPath = File::getTempDir() . $profilePath;

			$path = 'data://tv/SmallPersons' . $profilePath;
			$person->small_image_date = $this->saveImage($profilePath, 'w138_and_h175_face', $path, $tempPath);

			$path = 'data://tv/LargePersons' . $profilePath;
			$person->large_image_date = $this->saveImage($profilePath, 'w300_and_h450_bestv2', $path, $tempPath);
		}

		$person->saveIfChanged();
	}

	public function afterUpdate()
	{
	}
}
