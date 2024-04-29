<?php

namespace XC\ThreadGPTBot\XF\Pub\View;

use XF\Mvc\View;

class Bot extends View
{

    public function renderJson()
    {


        $content = $this->params['botcontent'];

        // var_dump($content);exit;

        // $templater = $this->renderer->getTemplater();
        // $html = $this->renderTemplate('public:bot_content', ['content' => ltrim($content)]);

        return [
            // 'botcontent' => $this->renderer->getHtmlOutputStructure($html),
            'botcontent' => $content,
        ];
    }
}
