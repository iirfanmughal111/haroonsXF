<?php

namespace Snog\Movies\Service\Person;

use Snog\Movies\Tmdb\Image;
use XF\Util\File;

class Preparer extends \XF\Service\AbstractService
{
	/**
	 * @var \Snog\Movies\Entity\Person
	 */
	protected $person;

	public function __construct(\XF\App $app, \Snog\Movies\Entity\Person $company)
	{
		parent::__construct($app);
		$this->person = $company;
	}

	public function getPerson()
	{
		return $this->person;
	}

	public function saveImage($srcPath, $size, $localPath, $tempPath)
	{
		$tmdbApi = new Image();
		$poster = $tmdbApi->getImage($srcPath, $size, $lastModified);
		if ($tmdbApi->hasError())
		{
			return 0;
		}

		if (file_exists($tempPath))
		{
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
		if (!empty($this->person->profile_path))
		{
			$profilePath = $this->person->profile_path;
			$tempPath = File::getTempDir() . $profilePath;

			$path = 'data://movies/SmallPersons' . $profilePath;
			$person->small_image_date =$this->saveImage($profilePath, 'w138_and_h175_face', $path, $tempPath);

			$path = 'data://movies/LargePersons' . $profilePath;
			$person->large_image_date =$this->saveImage($profilePath, 'w300_and_h450_bestv2', $path, $tempPath);
		}

		$person->saveIfChanged();
	}

	public function afterUpdate()
	{
	}
}