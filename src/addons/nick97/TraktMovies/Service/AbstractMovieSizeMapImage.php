<?php

namespace nick97\TraktMovies\Service;

use XF\Util\File;

abstract class AbstractMovieSizeMapImage extends \XF\Service\AbstractService
{
	protected $allowedTypes = [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG];

	protected $fileName;
	protected $error = null;

	public abstract function getImageSizeMap();
	public abstract function deleteImages();
	public abstract function deleteImageFiles();

	protected abstract function saveImageFile($size, $file);

	public function getError()
	{
		return $this->error;
	}

	public function setImage($fileName)
	{
		if (!$this->validateImage($fileName, $error)) {
			$this->error = $error;
			$this->fileName = null;
			return false;
		}

		$this->fileName = $fileName;
		return true;
	}

	public function setImageFromApiPath($srcPath, $size = 'original')
	{
		$traktApi = new \nick97\TraktMovies\Trakt\Image();
		$url = $traktApi->getImageUrl($srcPath, $size);

		$tempFile = File::getTempFile();
		$response = $this->app->http()->reader()->getUntrusted($url, [], $tempFile);

		if (!$response || $response->getStatusCode() !== 200) {
			$this->error = $response ? $response->getReasonPhrase() : \XF::phrase('error_occurred_while_uploading_files');
			return false;
		}

		$this->fileName = $tempFile;

		return true;
	}

	public function updateImage()
	{
		if (!$this->fileName) {
			return false;
		}

		$outputFiles = [];
		$baseFile = $this->fileName;

		$sizeMap = $this->getImageSizeMap();

		$imageManager = $this->app->imageManager();
		foreach ($sizeMap as $code => $size) {
			if (isset($outputFiles[$code])) {
				continue;
			}

			$image = $imageManager->imageFromFile($baseFile);
			if (!$image) {
				continue;
			}

			$this->resizeImage($image, $size);

			$newTempFile = File::getTempFile();
			if ($newTempFile && $image->save($newTempFile)) {
				$outputFiles[$code] = $newTempFile;
			}
			unset($image);
		}

		if (count($outputFiles) != count($sizeMap)) {
			throw new \RuntimeException("Failed to save image to temporary file; image may be corrupt or check internal_data/data permissions");
		}

		foreach ($outputFiles as $code => $file) {
			$this->saveImageFile($code, $file);
		}

		return false;
	}

	protected function resizeImage(\XF\Image\AbstractDriver $image, $size)
	{
		$image->resizeHeight($size);
	}

	public function validateImage($fileName, &$error = null)
	{
		$error = null;

		if (!file_exists($fileName)) {
			throw new \InvalidArgumentException("Invalid file '$fileName' passed to icon service");
		}
		if (!is_readable($fileName)) {
			throw new \InvalidArgumentException("'$fileName' passed to icon service is not readable");
		}

		$imageInfo = filesize($fileName) ? getimagesize($fileName) : false;
		if (!$imageInfo) {
			$error = \XF::phrase('provided_file_is_not_valid_image');
			return false;
		}

		$type = $imageInfo[2];
		if (!in_array($type, $this->allowedTypes)) {
			$error = \XF::phrase('provided_file_is_not_valid_image');
			return false;
		}

		$width = $imageInfo[0];
		$height = $imageInfo[1];

		if (!$this->app->imageManager()->canResize($width, $height)) {
			$error = \XF::phrase('uploaded_image_is_too_big');
			return false;
		}

		return true;
	}
}
