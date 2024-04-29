<?php

namespace HoU\Attachment\XF\Pub\View\Reaction;

use XF\Mvc\View;

class React extends XFCP_React {

    public function renderJson() {
        
        
      
        $content = $this->params['content'];
        $link = $this->params['link'];

        $templater = $this->renderer->getTemplater();
        $html = $this->renderTemplate('public:reaction_react', $this->getParams());
        $appendAttachment = $this->renderTemplate('public:reaction_attachment', ['content' => $content]);
       
        $reactionList = $templater->func('reactions', [$content, $link]);

        return [
            'postid' => $content->post_id,
             'appendattach' => $this->renderer->getHtmlOutputStructure($appendAttachment), 
            'html' => $this->renderer->getHtmlOutputStructure($html),
            'reactionList' => $this->renderer->getHtmlOutputStructure($reactionList)
        ];
    }

}
