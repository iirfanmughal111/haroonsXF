<?php

namespace FS\DownloadThreadAttachments\XF\Pub\Views;

class DownloadZip extends \XF\Mvc\View
{
    public function renderRaw()
    {
        $rootPath = \XF::getRootDirectory();

        $zipFile = $this->params['zipFile'];
        $fileName = $this->params['fileName'];
        $dirPath = $this->params['dirPath'];

        $this->response
            ->setAttachmentFileParams($fileName, 'zip')
            ->setDownloadFileName($fileName);

        if (file_exists($dirPath)) {
            \XF\Util\File::deleteDirectory($dirPath);
        }

        readfile($zipFile);
        unlink($rootPath . '/' . $fileName);
    }
}
