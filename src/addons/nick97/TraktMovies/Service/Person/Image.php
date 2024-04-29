<?php

namespace nick97\TraktMovies\Service\Person;


use nick97\TraktMovies\Service\AbstractMovieSizeMapImage;
use XF\Util\File;

class Image extends AbstractMovieSizeMapImage
{
	/**
	 * @var \nick97\TraktMovies\Entity\Person
	 */
	protected $person;

	public function __construct(\XF\App $app, \nick97\TraktMovies\Entity\Person $person)
	{
		parent::__construct($app);

		$this->person = $person;
	}

	public function getImageSizeMap()
	{
		return [
			'l' => [450, 675],
			's' => [138, 175]
		];
	}

	protected function saveImageFile($size, $file)
	{
		$dataFile = $this->person->getAbstractedImagePath($size);
		if ($dataFile) {
			File::copyFileToAbstractedPath($file, $dataFile);
		}

		$this->person->fastUpdate('large_image_date', \XF::$time);
	}

	protected function resizeImage(\XF\Image\AbstractDriver $image, $size)
	{
		$image->resizeAndCrop($size[0], $size[1]);
	}

	public function deleteImages()
	{
		$this->deleteImageFiles();

		$this->person->large_image_date = 0;
		$this->person->profile_path = '';
		$this->person->save();

		return true;
	}

	public function deleteImageFiles()
	{
		if ($this->person->profile_path) {
			File::deleteFromAbstractedPath($this->person->getAbstractedImagePath('s'));
			File::deleteFromAbstractedPath($this->person->getAbstractedImagePath('l'));
		}
	}
}
