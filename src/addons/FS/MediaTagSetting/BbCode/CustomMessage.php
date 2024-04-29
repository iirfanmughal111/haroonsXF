<?php

namespace FS\MediaTagSetting\BbCode;

class CustomMessage
{
    public static function renderTagCustomMsg($tagChildren, $tagOption, $tag, array $options, \XF\BbCode\Renderer\AbstractRenderer $renderer)
    {
        $viewParams = [
            
            'customMessage' => isset($tag['children'][0]) ? $tag['children'][0] : 'Default message for media tags'
        ];

        return $renderer->getTemplater()->renderTemplate('public:fs_custom_message', $viewParams);
    }
}