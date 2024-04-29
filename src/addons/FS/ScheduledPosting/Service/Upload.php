<?php

namespace FS\ScheduledPosting\Service;


use FS\ScheduledPosting\Entity\ScheduledPosting;
use XF\Entity\User;
class Upload extends \XF\Service\AbstractService 
{

    protected $doc;
    protected $error = null;
//    protected $allowedTypes = [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG];
    protected $fileName;
    protected $extension;
    protected $throwErrors = true; 

    public function __construct(\XF\App $app, ScheduledPosting $document) {
        parent::__construct($app);
        $this->setdoc($document);
    }

    protected function setdoc(ScheduledPosting $document) {
       

        $this->doc = $document;
    }

    public function getError() {
        return $this->error;
    }

    public function setDocFromUpload($upload) {


         $this->fileName = $upload->getTempFile();
         $this->extension=$upload->getExtension();
        return true;

        return $this->setPathAndExtension($upload->getTempFile());
    }



    public function uploadDocument($upload,$type) 
    {
        
       

        if (!$this->fileName) {
            return $this->throwException(new \LogicException("No source file for image set"));
        }
        if (!$this->doc->exists()) {
            return $this->throwException(new \LogicException("Image does not exist, cannot update image"));
        }


        $dataFile = $this->doc->getAbstractedCustomdocPath($this->extension,$type);
          \XF\Util\File::copyFileToAbstractedPath($this->fileName, $dataFile);
        
            
         
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