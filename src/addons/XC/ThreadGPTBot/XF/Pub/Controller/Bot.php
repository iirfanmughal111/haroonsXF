<?php

namespace XC\ThreadGPTBot\XF\Pub\Controller;

use XF\Pub\Controller\AbstractController;
use XF\Mvc\ParameterBag;

class Bot extends AbstractController {

    public function actiongptArticle() {

        $visitor = \xf::visitor();

        if (!$visitor->hasArticlePermission("bot_gpt_article")) {

            throw $this->exception($this->noPermission());
        }


        $viewParams = [
            'botOptions' => $this->finder('XC\ThreadGPTBot:BotOption')->fetch()
        ];
        return $this->view('AThreadGPTBots:Forum', 'text_box_article', $viewParams);
    }

    public function actiongptBot(ParameterBag $params) {


        // $visitor = \xf::visitor();

        // if (!$visitor->hasArticlePermission("bot_gpt_article")) {

        //     throw $this->exception($this->noPermission());
        // }
        // $article = $this->filter('article', 'str');
        // $botOption = $this->filter('option_bot', 'str');

        // if (!$article && !$botOption) {

        //     throw $this->exception($this->notFound(\XF::phrase("bot_field_requied")));
        // }
        // $bot = $this->service('XC\ThreadGPTBot:Bot');

        // $threadDescription = $bot->getThreadDescription($article, $botOption);

        // if(isset($threadDescription['error'])){
            
        //     throw $this->exception($this->notFound($threadDescription['error']['message']));
        // }
       
        $viewParams = [
            'botcontent' => "helllo",
        ];

        return $this->view('XC\ThreadGPTBot\XF:Bot', '', $viewParams);
    }

}
