<?php

namespace Snog\TV\Service\TVPost;

use XF\Util\File;

class Image extends \XF\Service\AbstractService
{
	/**
	 * @var \Snog\TV\Entity\TVPost
	 */
	protected $episode;

	protected $fileName;
	protected $error = null;

	protected $allowedTypes = [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG];

	public function __construct(\XF\App $app, \Snog\TV\Entity\TVPost $episode)
	{
		parent::__construct($app);

		$this->episode = $episode;
	}

	public function getError()
	{
		return $this->error;
	}

	public function setImage($fileName)
	{
		if (!$this->validateImage($fileName, $error))
		{
			$this->error = $error;
			$this->fileName = null;
			return false;
		}

		$this->fileName = $fileName;
		return true;
	}

	public function setImageFromApiPath($srcPath, $size)
	{
		if (empty($srcPath))
		{
			return false;
		}

		$tmdbApi = new \Snog\TV\Tmdb\Image();
		$url = $tmdbApi->getImageUrl($srcPath, $size);

		$tempFile = File::getTempFile();
		$response = $this->app->http()->reader()->getUntrusted($url, [], $tempFile);

		if ($response->getStatusCode() !== 200)
		{
			return false;
		}

		$this->fileName = $tempFile;

		return true;
	}

	public function validateImage($fileName, &$error = null)
	{
		$error = null;

		if (!file_exists($fileName))
		{
			throw new \InvalidArgumentException("Invalid file '$fileName' passed to icon service");
		}
		if (!is_readable($fileName))
		{
			throw new \InvalidArgumentException("'$fileName' passed to icon service is not readable");
		}

		$imageInfo = filesize($fileName) ? getimagesize($fileName) : false;
		if (!$imageInfo)
		{
			$error = \XF::phrase('provided_file_is_not_valid_image');
			return false;
		}

		$type = $imageInfo[2];
		if (!in_array($type, $this->allowedTypes))
		{
			$error = \XF::phrase('provided_file_is_not_valid_image');
			return false;
		}

		$width = $imageInfo[0];
		$height = $imageInfo[1];

		if (!$this->app->imageManager()->canResize($width, $height))
		{
			$error = \XF::phrase('uploaded_image_is_too_big');
			return false;
		}

		return true;
	}

	public function updateImage()
	{
		if ($this->fileName)
		{
			$dataFile = $this->episode->getAbstractedImagePath();
			File::copyFileToAbstractedPath($this->fileName, $dataFile);
		}
		else
		{
			$this->episode->tv_image = '';
		}

		return true;
	}

	public function deleteImage()
	{
		$this->deleteImageFile();

		$this->episode->tv_image = '';
		$this->episode->save();

		return true;
	}

	public function deleteImageFile()
	{
		if ($this->episode->tv_image)
		{
			File::deleteFromAbstractedPath($this->episode->getAbstractedImagePath());
		}
	}
}