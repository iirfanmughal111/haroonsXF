<?php

namespace nick97\TraktTV\Service\TV;


use nick97\TraktTV\Service\AbstractTvSizeMapImage;
use XF\Util\File;

class Image extends AbstractTvSizeMapImage
{
	/**
	 * @var \nick97\TraktTV\Entity\TV
	 */
	protected $tv;

	public function getImageSizeMap()
	{
		return [
			'l' => 0,
			's' => $this->app->options()->traktTvThreads_smallPosterWidth
		];
	}

	protected $allowedTypes = [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG];

	public function __construct(\XF\App $app, \nick97\TraktTV\Entity\TV $tv)
	{
		parent::__construct($app);

		$this->tv = $tv;
	}

	public function setImageFromApiPath($srcPath, $size = 'original')
	{
		$this->tv->tv_image = $srcPath;
		return parent::setImageFromApiPath($srcPath, $size);
	}

	protected function saveImageFile($size, $file)
	{
		$dataFile = $this->tv->getAbstractedImagePath($size);
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

		$this->tv->tv_image = '';
		$this->tv->save();

		return true;
	}

	public function deleteImageFiles()
	{
		if ($this->tv->tv_image) {
			File::deleteFromAbstractedPath($this->tv->getAbstractedImagePath('s'));
			File::deleteFromAbstractedPath($this->tv->getAbstractedImagePath('l'));
		}
	}
}
