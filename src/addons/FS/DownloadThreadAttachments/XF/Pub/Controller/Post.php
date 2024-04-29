<?php

namespace FS\DownloadThreadAttachments\XF\Pub\Controller;

use XF\Mvc\ParameterBag;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class Post extends XFCP_Post
{
    public function actionExportAttachments(ParameterBag $params)
    {
        $post = $this->assertViewablePost($params->post_id);

        if (!$post->Thread->canViewAttachments()) {
            return $this->noPermission();
        }

        if ($this->isPost()) {
            $rootPath = \XF::getRootDirectory();
            $visitor = \XF::visitor();

            $fileName = $post->Thread->title . '-' . $post->post_id . '-' . $visitor->user_id . '-All-Attachments-' . date("Y-m-d");

            $destinationDirPath = $rootPath . '/internal_data/fs_thread_attachments/' .  $fileName;

            $attachments = $post->Attachments;

            if (!count($attachments)) throw $this->exception($this->notFound(\XF::phrase('fs_no_attachments_exist_in_this_post')));

            foreach ($attachments as $attachment) {
                $sourcePath = $rootPath . '/' . sprintf('internal_data/attachments/%d/%d-' . $attachment->Data->file_hash . '.data', floor($attachment->data_id / 1000), $attachment->data_id);

                if (!file_exists($destinationDirPath)) {
                    mkdir($destinationDirPath, 0777, true);
                }

                if (file_exists($sourcePath)) {
                    copy($sourcePath, $destinationDirPath . '/' . $attachment->getFilename());
                }
            }

            $fileName .= '.zip';

            $finalZip = $this->MakeZip($destinationDirPath, $fileName, $download = true);

            $this->setResponseType('raw');
            $viewParams = [
                'zipFile' => $finalZip,
                'fileName' =>  $fileName,
                'dirPath' => $destinationDirPath
            ];

            return $this->view('FS\DownloadThreadAttachments\XF\Pub\Views\DownloadZip', '', $viewParams);
        }


        return $this->view('FS\DownloadThreadAttachments:Post', 'fs_export_post_attachments_confirm', ['post' => $post]);
    }

    public function MakeZip($rootPath, $zipName, $download)
    {
        $zip = new \ZipArchive();

        $zip->open($zipName, \ZipArchive::CREATE);



        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );


        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
        return $zip->filename;
    }
}
