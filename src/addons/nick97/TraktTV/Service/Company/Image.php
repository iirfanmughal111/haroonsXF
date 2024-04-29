<?php

namespace nick97\TraktTV\Service\Company;


use nick97\TraktTV\Service\AbstractTvSizeMapImage;
use XF\Util\File;

class Image extends AbstractTvSizeMapImage
{
	/**
	 * @var \nick97\TraktTV\Entity\Company
	 */
	protected $company;

	public function getImageSizeMap()
	{
		return [
			'l' => 0,
			's' => $this->app->options()->traktTvThreads_smallCompanyLogoWidth
		];
	}

	protected $allowedTypes = [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG];

	public function __construct(\XF\App $app, \nick97\TraktTV\Entity\Company $company)
	{
		parent::__construct($app);

		$this->company = $company;
	}

	protected function saveImageFile($size, $file)
	{
		$dataFile = $this->company->getAbstractedImagePath($size);
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

		$this->company->image_url = '';
		$this->company->save();

		return true;
	}

	public function deleteImageFiles()
	{
		if ($this->company->image_url) {
			File::deleteFromAbstractedPath($this->company->getAbstractedImagePath('s'));
			File::deleteFromAbstractedPath($this->company->getAbstractedImagePath('l'));
		}
	}
}
