<?php

namespace FS\HideContentWithPreview\BbCode;

use XF\PreEscaped;

class Locked
{
    public static function renderTagLocked($tagChildren, $tagOption, $tag, array $options, \XF\BbCode\Renderer\AbstractRenderer $renderer)
    {
        //        $content = $renderer->renderSubTreePlain($tagChildren);
        $content = $renderer->renderSubTree($tagChildren, $options);

        if ($content === '') {
            return '';
        }

        $thumbnailUrl = null;
        $post = $options["entity"];

        // $firstAttachment = $post->Attachments->first();

        // get first attachment form those attachments which is embeded as image

        if (isset($post->embed_metadata["attachments"])) {
            $attachmentIds  = $post->embed_metadata["attachments"];
            $firstAttachId = reset($attachmentIds);  // get first attachment-id form those attachments which is embeded as image

            $firstAttachment = \XF::app()->find('XF:Attachment', $firstAttachId);

            if ($firstAttachment) {
                $thumbnailUrl = $firstAttachment->getThumbnailUrlFull();
            }
        }

        //        $visitor = \XF::visitor();
        //        $options = \XF::options();
        //        
        //        $hideContent = false;
        //        
        //        $userGroupIds = $options->fs_hcwp_userGroups;
        //        if($visitor->isMemberOf($userGroupIds))
        //        {
        //            $hideContent = true;
        //        }

        $viewParams = [

            'thumbnailUrl' => $thumbnailUrl,
            //            'hideContent' => $hideContent,
            //            'content' => new PreEscaped($content)
        ];

        return $renderer->getTemplater()->renderTemplate('public:fs_hideContent_with_preview', $viewParams);
    }
}
