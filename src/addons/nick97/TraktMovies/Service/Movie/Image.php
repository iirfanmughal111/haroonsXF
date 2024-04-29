<?php

namespace nick97\TraktMovies\Service\Movie;


use nick97\TraktMovies\Service\AbstractMovieSizeMapImage;
use XF\Util\File;

class Image extends AbstractMovieSizeMapImage
{
	/**
	 * @var \nick97\TraktMovies\Entity\Movie
	 */
	protected $movie;

	public function getImageSizeMap()
	{
		return [
			'l' => 0,
			's' => $this->app->options()->traktthreads_smallPosterWidth
		];
	}

	protected $allowedTypes = [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG];

	public function __construct(\XF\App $app, \nick97\TraktMovies\Entity\Movie $movie)
	{
		parent::__construct($app);

		$this->movie = $movie;
	}

	public function setImageFromApiPath($srcPath, $size = 'original')
	{
		$this->movie->trakt_image = $srcPath;
		return parent::setImageFromApiPath($srcPath, $size);
	}

	protected function saveImageFile($size, $file)
	{
		$dataFile = $this->movie->getAbstractedImagePath($size);
		if ($dataFile) {
			File::copyFileToAbstractedPath($file, $dataFile);
		}
	}

	protected function resizeImage(\XF\Image\AbstractDriver $image, $size)
	{
		if ($size != 0) {
			$image->resizeWidth($size);
		}
	}

	public function deleteImages()
	{
		$this->deleteImageFiles();

		$this->movie->trakt_image = '';
		$this->movie->save();

		return true;
	}

	public function deleteImageFiles()
	{
		if ($this->movie->trakt_image) {
			File::deleteFromAbstractedPath($this->movie->getAbstractedImagePath('s'));
			File::deleteFromAbstractedPath($this->movie->getAbstractedImagePath('l'));
		}
	}
}
