<?php

namespace XenBulletin\VideoByRon\Service;

class Upload extends \XF\Service\AbstractService 
{
    protected $error = null;
    protected $allowedTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG];
    protected $fileName;
    protected $width;
    protected $height;
    protected $type;
    protected $throwErrors = true; 

    public function __construct(\XF\App $app) {
        parent::__construct($app);
    }

    public function getError() {
        return $this->error;
    }

    public function setImageFromUpload(\XF\Http\Upload $upload) {
        
        $upload->requireImage();

        if (!$upload->isValid($errors)) {
            $this->error = reset($errors);
            return false;
        }

        return $this->setImage($upload->getTempFile());
    }

    public function setImage($fileName) {
        if (!$this->validateImageAsSig($fileName, $error)) {
            $this->error = $error;
            $this->fileName = null;
            return false;
        }

        $this->fileName = $fileName;
        return true;
    }

    public function validateImageAsSig($fileName, &$error = null) {
        $error = null;

        if (!file_exists($fileName)) {
            return $this->throwException(new \InvalidArgumentException("Invalid file '$fileName' passed to upload service"));
        }
        if (!is_readable($fileName)) {
            return $this->throwException(new \InvalidArgumentException("'$fileName' passed to upload service is not readable"));
        }

        $imageInfo = filesize($fileName) ? @getimagesize($fileName) : false;
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
        $options = \XF::options();

        $filesize = filesize($fileName) / 1024;

//        if ($filesize > $options->imgMaxFileSize) {
//            $error = 'Uploaded image file is too big (' . ceil($filesize) . ' KB). Maximum allowed size is ' . $options->imgMaxFileSize . ' KB.';
//            return FALSE;
//        }
//
//        if ($options->imgMaxWidth > 0) {
//            if ($width > $options->imgMaxWidth) {
//                $error = 'Uploaded image file is too wide (' . $width . ' px). Maximum allowed width is ' . $options->imgMaxWidth . ' pixels.';
//                return FALSE;
//            }
//        }
//        if ($options->imgMaxHeight > 0) {
//            if ($height > $options->imgMaxHeight) {
//                $error = 'Uploaded image file is too high (' . $height . ' px). Maximum allowed height is ' . $options->imgMaxHeight . ' pixels.';
//                return FALSE;
//            }
//        }


        $this->width = $width;
        $this->height = $height;
        $this->type = $type;
        
        return true;
    }

    public function updateImage() 
    {
        if (!$this->fileName) {
            return $this->throwException(new \LogicException("No source file for image set"));
        }

        $imageManager = $this->app->imageManager();
        $outputFiles = [];
        $baseFile = $this->fileName;
        $image = $imageManager->imageFromFile($this->fileName);
        if (!$image) {
            return false;
        }
    //-------------------  resize image -----------------
//        if($this->height > 236)
//        {
//            if ($image instanceof \XF\Image\Imagick) {
//                // Workaround to only use the first frame of a multi-frame image for the thumb
//                foreach ($image->getImage() AS $imagick) {
//                    $image->setImage($imagick->getImage());
//                    break;
//                }
//            }
//
//            $width = 509;
//            $height = 236;
//
//            $image->resizeAndCrop($width, $height)
//                    ->unsharpMask();
//        }

    // ---------------------------------------------------------

        $newTempFile = \XF\Util\File::getTempFile();
        
        if ($newTempFile && $image->save($newTempFile)) 
        {
            $path = 'data://brand_img/logo_images/ron-logo.jpg';
            
            \XF\Util\File::copyFileToAbstractedPath($newTempFile, $path);
        } 
        else
        {
            return $this->throwException(new \RuntimeException("Failed to save image to temporary file; check internal_data/data permissions"));
        }

        unset($image);

        return true;
    }
    
    
    protected function throwException(\Exception $error)
    {
        if ($this->throwErrors)
        {
                throw $error;
        }
        else
        {
                return false;
        }
    }

}
