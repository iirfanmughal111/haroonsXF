<?php

namespace Snog\TV\Service\TVForum;


use Snog\TV\Service\AbstractTvSizeMapImage;
use XF\Util\File;

class Image extends AbstractTvSizeMapImage
{
	/**
	 * @var \Snog\TV\Entity\TVForum
	 */
	protected $tvForum;

	public function getImageSizeMap()
	{
		return [
			'l' => 0
		];
	}

	protected $allowedTypes = [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG];

	public function __construct(\XF\App $app, \Snog\TV\Entity\TVForum $tvForum)
	{
		parent::__construct($app);

		$this->tvForum = $tvForum;
	}

	protected function saveImageFile($size, $file)
	{
		$dataFile = $this->tvForum->getAbstractedImagePath();
		if ($dataFile)
		{
			File::copyFileToAbstractedPath($file, $dataFile);
		}
	}

	protected function resizeImage(\XF\Image\AbstractDriver $image, $size)
	{
	}

	public function deleteImages()
	{
		$this->deleteImageFiles();

		$this->tvForum->tv_image = '';
		$this->tvForum->save();

		return true;
	}

	public function deleteImageFiles()
	{
		if ($this->tvForum->tv_image)
		{
			File::deleteFromAbstractedPath($this->tvForum->getAbstractedImagePath());
		}
	}
}