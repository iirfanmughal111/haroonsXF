<?php

namespace FS\DownloadThreadAttachments\XF\Pub\Controller;

use XF\Mvc\ParameterBag;
use ZipArchive;
//use XF\Http\Response;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class Thread extends XFCP_Thread
{

    // public function actionExportAttachments(ParameterBag $params)
    // {
    //     $thread = $this->assertViewableThread($params->thread_id);

    //     if (!$thread->canViewAttachments()) {
    //         return $this->noPermission();
    //     }

    //     if ($this->isPost()) {
    //         $rootPath = \XF::getRootDirectory();
    //         $visitor = \XF::visitor();

    //         $fileName = $thread->title . '-' . $thread->thread_id . '-' . $visitor->user_id . '-All-Attachments-' . date("Y-m-d");

    //         $destinationDirPath = $rootPath . '/internal_data/fs_thread_attachments/' .  $fileName;

    //         $postIds = $thread->getAttachmentPostIds();
    //         if (!$postIds) {
    //             throw $this->exception($this->notFound(\XF::phrase('fs_no_attachments_exist_in_this_thread')));
    //         }

    //         $attachments = $this->getAttachmentRepo()->findAttachmentsByContent('post', $postIds)->fetch();

    //         if (!count($attachments)) throw $this->exception($this->notFound(\XF::phrase('fs_no_attachments_exist_in_this_thread')));

    //         foreach ($attachments as $attachment) {
    //             $sourcePath = $rootPath . '/' . sprintf('internal_data/attachments/%d/%d-' . $attachment->Data->file_hash . '.data', floor($attachment->data_id / 1000), $attachment->data_id);

    //             if (!file_exists($destinationDirPath)) {
    //                 mkdir($destinationDirPath, 0777, true);
    //             }

    //             if (file_exists($sourcePath)) {
    //                 copy($sourcePath, $destinationDirPath . '/' . $attachment->getFilename());
    //             }
    //         }



    //         $fileName .= '.zip';

    //         $finalZip = $this->MakeZip($destinationDirPath, $fileName, $download = true);

    //         $this->setResponseType('raw');
    //         $viewParams = [
    //             'zipFile' => $finalZip,
    //             'fileName' =>  $fileName,
    //             'dirPath' => $destinationDirPath
    //         ];

    //         return $this->view('FS\DownloadThreadAttachments\XF\Pub\Views\DownloadZip', '', $viewParams);
    //     }


    //     return $this->view('FS\DownloadThreadAttachments:Thread', 'fs_export_thread_attachments_confirm', ['thread' => $thread]);
    // }

    // public function actionExportPostAttachments(ParameterBag $params)
    // {
    //     $thread = $this->assertViewableThread($params->thread_id);

    //     if (!$thread->canViewAttachments()) {
    //         return $this->noPermission();
    //     }

    //     if ($this->isPost()) {
    //         $rootPath = \XF::getRootDirectory();
    //         $visitor = \XF::visitor();

    //         $fileName = $thread->title . '-' . $thread->thread_id . '-' . $visitor->user_id . '-All-Attachments-' . date("Y-m-d");

    //         $destinationDirPath = $rootPath . '/internal_data/fs_thread_attachments/' .  $fileName;

    //         $postIds = $thread->getAttachmentPostIds();
    //         if (!$postIds) {
    //             throw $this->exception($this->notFound(\XF::phrase('fs_no_attachments_exist_in_this_thread')));
    //         }

    //         $attachments = $this->getAttachmentRepo()->findAttachmentsByContent('post', $postIds)->fetch();

    //         if (!count($attachments)) throw $this->exception($this->notFound(\XF::phrase('fs_no_attachments_exist_in_this_thread')));

    //         foreach ($attachments as $attachment) {
    //             $sourcePath = $rootPath . '/' . sprintf('internal_data/attachments/%d/%d-' . $attachment->Data->file_hash . '.data', floor($attachment->data_id / 1000), $attachment->data_id);

    //             if (!file_exists($destinationDirPath)) {
    //                 mkdir($destinationDirPath, 0777, true);
    //             }

    //             if (file_exists($sourcePath)) {
    //                 copy($sourcePath, $destinationDirPath . '/' . $attachment->getFilename());
    //             }
    //         }



    //         $fileName .= '.zip';

    //         $finalZip = $this->MakeZip($destinationDirPath, $fileName, $download = true);

    //         $this->setResponseType('raw');
    //         $viewParams = [
    //             'zipFile' => $finalZip,
    //             'fileName' =>  $fileName,
    //             'dirPath' => $destinationDirPath
    //         ];

    //         return $this->view('FS\DownloadThreadAttachments\XF\Pub\Views\DownloadZip', '', $viewParams);
    //     }


    //     return $this->view('FS\DownloadThreadAttachments:Thread', 'fs_export_thread_attachments_confirm', ['thread' => $thread]);
    // }

    // public function MakeZip($rootPath, $zipName, $download)
    // {
    //     $zip = new \ZipArchive();

    //     $zip->open($zipName, \ZipArchive::CREATE);



    //     $files = new RecursiveIteratorIterator(
    //         new RecursiveDirectoryIterator($rootPath),
    //         RecursiveIteratorIterator::LEAVES_ONLY
    //     );


    //     foreach ($files as $name => $file) {
    //         if (!$file->isDir()) {
    //             $filePath = $file->getRealPath();
    //             $relativePath = substr($filePath, strlen($rootPath) + 1);
    //             $zip->addFile($filePath, $relativePath);
    //         }
    //     }
    //     return $zip->filename;
    // }


    //    
    //    protected function getAbstractedDestinationFileName(\XF\Entity\Thread $thread)
    //    {
    //        return 'internal-data://fs_thread_attachments/' .  $thread->title . '-' . $thread->thread_id;
    //    }

    // protected function getAttachmentRepo()
    // {
    //     return $this->repository('XF:XF:Attachment');
    // }
}
