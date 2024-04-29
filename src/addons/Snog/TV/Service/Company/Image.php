<?php

namespace Snog\TV\Service\Company;


use Snog\TV\Service\AbstractTvSizeMapImage;
use XF\Util\File;

class Image extends AbstractTvSizeMapImage
{
	/**
	 * @var \Snog\TV\Entity\Company
	 */
	protected $company;

	public function getImageSizeMap()
	{
		return [
			'l' => 0,
			's' => $this->app->options()->TvThreads_smallCompanyLogoWidth
		];
	}

	protected $allowedTypes = [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG];

	public function __construct(\XF\App $app, \Snog\TV\Entity\Company $company)
	{
		parent::__construct($app);

		$this->company = $company;
	}

	protected function saveImageFile($size, $file)
	{
		$dataFile = $this->company->getAbstractedImagePath($size);
		if ($dataFile)
		{
			File::copyFileToAbstractedPath($file, $dataFile);
		}
	}

	protected function resizeImage(\XF\Image\AbstractDriver $image, $size)
	{
		if ($size != 0)
		{
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
		if ($this->company->image_url)
		{
			File::deleteFromAbstractedPath($this->company->getAbstractedImagePath('s'));
			File::deleteFromAbstractedPath($this->company->getAbstractedImagePath('l'));
		}
	}
}