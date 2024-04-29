<?php

namespace FS\BunnyIntegration\Job;

require __DIR__ . '/../../../XFAws/_vendor/autoload.php';

use Aws\S3\S3Client;

use XF\Job\AbstractJob;

class BunnyUpload extends AbstractJob
{
    protected $defaultData = [];

    public function run($maxRunTime)
    {
        $s = microtime(true);

        $app = \xf::app();

        $threadId = $this->data['threadId'];

        $thread = $app->em()->find('XF:Thread', $threadId);


        if (!empty($thread)) {

            $threadAttachments = $app->finder('XF:Attachment')
                ->where('content_id', $thread->FirstPost["post_id"])
                ->where('bunny_vid_id', '!=', null)
                ->where('is_bunny', '=', 0)
                ->fetch();

            if (!empty($threadAttachments)) {

                $bunnyService = $app->service('FS\BunnyIntegration\XF:BunnyServ');

                foreach ($threadAttachments as $attachmentSingle) {

                    if ($attachmentSingle->isvideo()) {

                        // $videoExtenion = $attachmentSingle->getExtension();

                        $filePath = \XF::getRootDirectory() . "/data/" . $attachmentSingle->Data->getPublicUrlBunnyPath();
                        // $filePath = "https://e-dewan.ams3.cdn.digitaloceanspaces.com" . "/data/" . $attachmentSingle->Data->getPublicUrlBunnyPath();

                        $binaryVideo = file_get_contents($filePath);

                        $uploadVideoRes = $bunnyService->uploadBunnyVideo($binaryVideo, $attachmentSingle['bunny_vid_id']);

                        if ($uploadVideoRes["success"] == true && $uploadVideoRes["statusCode"]  == 200) {

                            $oldMessage = $thread->FirstPost["message"];

                            $bunnyBBCode = "[fsbunny=" . \XF::options()->fs_bi_libraryId . "]" . $attachmentSingle['bunny_vid_id'] . "[/fsbunny]";

                            $attachmentId = '[ATTACH type="full"]' . $attachmentSingle->attachment_id . '[/ATTACH]';

                            if (strpos($oldMessage, $attachmentId) !== false) {

                                $newMessage = str_replace($attachmentId, $bunnyBBCode, $oldMessage);

                                $thread->FirstPost->fastUpdate('message', $newMessage);
                            }

                            $attachmentSingle->fastUpdate('is_bunny', 1);

                            $this->insertBunnyData($threadId, \XF::options()->fs_bi_libraryId, $attachmentSingle['bunny_vid_id']);
                        }
                    }
                }
            }

            $bunnyAttachs = $this->BunnyAttachs($threadId);

            if (count($bunnyAttachs)) {

                $s3 = $this->Aws();

                foreach ($bunnyAttachs as $bunnyAttach) {

                    $filePath =  "data/" . $bunnyAttach->Data->getPublicUrlBunnyPath();

                    $s3->deleteObject(['Bucket' => 'e-dewan', 'Key' => $filePath]);

                    $bunnyAttach->delete();
                }
            }


            return $this->complete();
        }

        return $this->resume();
    }

    public function getStatusMessage()
    {
    }

    public function canCancel()
    {
        return true;
    }

    public function canTriggerByChoice()
    {
        return true;
    }

    public function insertBunnyData($thread_id, $lib_id, $vid_id)
    {
        $threadBunnyVideo = \XF::app()->em()->create('FS\BunnyIntegration:DeleteBunnyVid');

        $threadBunnyVideo->thread_id = $thread_id;
        $threadBunnyVideo->bunny_library_id = $lib_id;
        $threadBunnyVideo->bunny_video_id = $vid_id;

        $threadBunnyVideo->save();
    }

    public function BunnyAttachs($threadId)
    {

        $app = \xf::app();

        return $app->finder('XF:Attachment')
            ->where('content_id', $threadId)
            ->where('bunny_vid_id', '!=', null)
            ->where('is_bunny', '=', 1)
            ->fetch();
    }

    public function Aws()
    {


        $s3 = new S3Client([

            'credentials' => [
                'key' => 'KJRCDY7LL4EWSUOPIE5J',
                'secret' => 'EpW5Heur+vORd9NhyQRLygiCWRl4ZQIP6c2l3hF78Cw'
            ],
            'region' => 'ams3',
            'version' => 'latest',
            'endpoint' => 'https://ams3.digitaloceanspaces.com'
        ]);

        return $s3;
    }






    // $objectsListResponse = $s3->listObjects(['Bucket' => "e-dewan"]);
    // $objects = $objectsListResponse['Contents'] ?? [];
    // echo '<pre>';
    // foreach ($objects as $object) {
    //     echo $object['Key'] . "\t" . $object['Size'] . "\t" . $object['LastModified'] . "\n";
    // }

    // $s3->deleteObject(['Bucket' => 'e-dewan', 'Key' => $videoPath]);
    //     $s3->deleteObject(['Bucket' => 'e-dewan', 'Key' => 'data/BunnyIntegration/584c3585-4d8b-403c-8ddb-4de9ae7c2014.mp4']);

    //     var_dump("lskdjfldsj");
    //     exit;
    // }
}
