<?php

namespace XC\ThreadGPTBot\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;


class BotOption extends AbstractController
{

        
       public function actionIndex()
       {
            $page = $this->filterPage();
            $perPage = 20;

            $options = $this->Finder('XC\ThreadGPTBot:BotOption');
            
            $total = $options->total();
            $this->assertValidPage($page, $perPage, $total, 'opt-bot');
            $options->limitByPage($page, $perPage);
            
            
            

            $viewParams = [

                    'options' => $options->order('id', 'DESC')->fetch(),
                
                    'page' => $page,
                    'perPage' => $perPage,
                    'total' => $total
            ];

            return $this->view('ThreadGPTBot:BotOption', 'bot_option_lists', $viewParams);
       }


//************************Add, Edit Function**********************************************

	   	public function OptionAddEdit($Option)
		{
                    $viewParams = [
                        'Option' => $Option,

                    ];

                    return $this->view('XC\ThreadGPTBot:BotOption', 'options_edit', $viewParams);
		}

		public function actionEdit(ParameterBag $params)
		{   
                    $Option = $this->assertOptionExists($params->id);

                    return $this->OptionAddEdit($Option);
		}

		public function actionAdd()
		{
			$Option = $this->em()->create('XC\ThreadGPTBot:BotOption');

			return $this->OptionAddEdit($Option);
		}




//************************Save option**********************************************
	protected function SaveProcess(\XC\ThreadGPTBot\Entity\BotOption $Option)
	{
		$form = $this->formAction();


		$input = $this->filter([
				'title' => 'STR',
                                'bot_instruction'=>'STR'
			]);

                
                $OptionName = $this->actionFind($input['title']);
               

		if (!$OptionName) 
		{
			$form->basicEntitySave($Option, $input);

			return $form;
		}
		else
		{
			if ($Option->id == $OptionName->id) 
			{
				$form->basicEntitySave($Option, $input);

				return $form;
			}
			else
			{
				$phraseKey = $OptionName->title." option already exists.";
		 		throw $this->exception(
					$this->notFound(\XF::phrase($phraseKey))
				);
			}
		}


	}

	public function actionSave(ParameterBag $params)
	{       
            $this->assertPostOnly();

            if ($params->id)
            {
                    $brandCategory = $this->assertOptionExists($params->id);
            }
            else
            {
                $brandCategory = $this->em()->create('XC\ThreadGPTBot:BotOption');
            }

            $this->SaveProcess($brandCategory)->run();

            return $this->redirect($this->buildLink('opt-bot'));
	}

//*********************************************************************************
        

        public function actionDelete(ParameterBag $params)
        {

                 $Option = $this->assertOptionExists($params->id);

                /** @var \XF\ControllerPlugin\Delete $plugin */
                $plugin = $this->plugin('XF:Delete');

                return $plugin->actionDelete(
                         $Option,
                        $this->buildLink('opt-bot/delete',  $Option),
                        $this->buildLink('opt-bot/edit',  $Option),
                        $this->buildLink('opt-bot'),
                         $Option->title
                );
        }


        protected function assertOptionExists($id, $with = null, $phraseKey = null)
        {
                return $this->assertRecordExists('XC\ThreadGPTBot:BotOption', $id, $with, $phraseKey);
        }


        public function actionFind($title)
        {

                $Option = $this->finder('XC\ThreadGPTBot:BotOption')->where('title',$title)->fetchOne();
                return $Option;

        }

}