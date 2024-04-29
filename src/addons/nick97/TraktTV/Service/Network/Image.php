<?php

namespace nick97\TraktTV\Service\Network;


use nick97\TraktTV\Service\AbstractTvSizeMapImage;
use XF\Util\File;

class Image extends AbstractTvSizeMapImage
{
	/**
	 * @var \nick97\TraktTV\Entity\Network
	 */
	protected $network;

	public function getImageSizeMap()
	{
		return [
			'l' => 0,
			's' => $this->app->options()->traktTvThreads_smallNetworkLogoWidth
		];
	}

	protected $allowedTypes = [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG];

	public function __construct(\XF\App $app, \nick97\TraktTV\Entity\Network $network)
	{
		parent::__construct($app);

		$this->network = $network;
	}

	protected function saveImageFile($size, $file)
	{
		$dataFile = $this->network->getAbstractedImagePath($size);
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

		$this->network->image_url = '';
		$this->network->save();

		return true;
	}

	public function deleteImageFiles()
	{
		if ($this->network->image_url) {
			File::deleteFromAbstractedPath($this->network->getAbstractedImagePath('s'));
			File::deleteFromAbstractedPath($this->network->getAbstractedImagePath('l'));
		}
	}
}
