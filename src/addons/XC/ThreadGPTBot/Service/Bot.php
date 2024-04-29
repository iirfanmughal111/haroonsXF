<?php

namespace XC\ThreadGPTBot\Service;

require __DIR__ . '../../vendor/autoload.php';

use Orhanerday\OpenAi\OpenAi;

class Bot extends \XF\Service\AbstractService {

    protected $apiKey = true;
    protected $totalWords = "";
    protected $content = "";
    protected $threadContent = "";
    protected $botOption = "";

    public function __construct(\XF\App $app) {
        parent::__construct($app);
        $apiKey = \xf::options()->bot_gpt_apikey;
        $this->apiKey = $apiKey;
    }

    public function GetContentBot($token, $content) {

        $botGptModel = \xf::options()->bot_gpt_model;

        
        if (is_array($botGptModel)) {

            if (isset($botGptModel['gptmodel']) && isset($botGptModel['gptmodel'])) {
               
                $gptModel = \xf::options()->bot_gpt_model['gptmodel'];

               
                if ($gptModel == 'gptChat') {

                    return $this->setChatModelBot(\xf::options()->bot_gpt_model['gptChat_model'], $token, $content);
                }
            
                


                if ($gptModel == 'gptDev') {

                        return $this->setDevModelBot(\xf::options()->bot_gpt_model['gptDev_model'], $token, $content);

                }
            }
        }

      
        $reponse=array();
        $reponse['error']['message']="Select Required Model......";
        return $reponse;
      
      
    }
    
    public function setChatModelBot($model,$token,$content){
        
        $openApi = new OpenAi($this->apiKey);

        $messages = [
            ['role' => 'user', 'content' => $content]
        ];

        $response = $openApi->chat([
            'model' => $model,
            'messages' => $messages,
            'temperature' => (float) \xf::options()->bot_gpt_temperature,
            'max_tokens' => 2000,
            'frequency_penalty' => (float) \xf::options()->bot_gpt_frequency,
            'presence_penalty' => (float) \xf::options()->bot_gpt_penalty,
            'user'=>$this->getUser(),
        ]);

        return  $this->getChatModelResponse($response);
         
        
    }
    
    public function setDevModelBot($model,$token,$content){
        
        
      
       
        $openApi = new OpenAi($this->apiKey);
        
        $response = $openApi->completion([
            
               'model' => $model,
               'prompt' => $content,
               'temperature' => (float) \xf::options()->bot_gpt_temperature,
               'max_tokens' => 2000,
               'frequency_penalty' => (float) \xf::options()->bot_gpt_frequency,
               'presence_penalty' =>(float) \xf::options()->bot_gpt_penalty,
               'user'=>$this->getUser(),
            ]);
        
       return $this->getDevModelResponse($response);
        
     
    }

    public function WordCount($content) {


        return str_word_count($content);
    }

    public function getThreadDescription($content, $botOption) {


        $wordCounts = $this->WordCount($content);

     
        $this->totalWords = $wordCounts;

        $this->content = $content;
        $this->botOption = $botOption;
        
      

        if ($wordCounts > 2000) {

            $loopIteration = $this->loopWordBase($wordCounts);


            $perLoop = 1000;

            $contentWords = "";
            foreach ($loopIteration as $key => $loop) {




                if ($key == 0) {

                    $contentWords = $this->getwords($this->content, 0, $perLoop);
                } else {

                    $contentWords = $this->getwords($this->content, $perLoop - 1000, $perLoop);
                }

                $perLoop = $perLoop + 1000;

                $botResponse = $this->GetContentBot(1000, $this->botOption . "\n" . $contentWords);

                

                if (isset($botResponse['error'])) {


                    return $botResponse;
                }
                $this->threadContent .= $botResponse;
            }


            return ucfirst($this->threadContent);
        }

        $botResponse = $this->GetContentBot($wordCounts, $this->botOption . "\n" . $content);

       

        if (isset($botResponse['error'])) {

            return $botResponse;
        }

        $this->threadContent .= $botResponse;
        return ucfirst($this->threadContent);
    }

    public function LoopWordBase($words) {


        $arrayIteration = ceil($words / 1000);

        return range(0, (int) $arrayIteration - 1);
    }

    public function getwordarray() {
        
    }

    public function getwords($content, $start, $end) {

        return implode(' ', array_slice(explode(' ', $content), $start, $end));
    }

     public function getDevModelResponse($response) {

        if (is_string($response)) {
            $response = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
        }

  
        if (isset($response['error'])) {

            return $response;
        }



        return $response['choices'][0]['text'] ?? '';
    }
    
    public function getChatModelResponse($response) {

        if (is_string($response)) {
            $response = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
        }

        if (isset($response['error'])) {

            return $response;
        }



        return $response['choices'][0]['message']['content'] ?? '';
    }
    
    public function getUser(){
        
         $session = \xf::app()->session();
         
         $visitor=\xf::visitor();
        
         return $visitor->user_id ? $visitor->username : $session->getSessionId();
       
    }

}
