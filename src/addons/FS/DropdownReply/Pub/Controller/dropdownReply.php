<?php

namespace FS\DropdownReply\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;

class dropdownReply extends AbstractController
{

    public function actionIndex(ParameterBag $params)
    {


        // $finder = $this->Finder('XF:Thread');
    $thread = $this->Finder('XF:Thread')->whereId($params->thread_id)->fetchOne();
  


        $viewpParams = ['dropdownReplys' => $thread, ];

     

        return $this->view('FS\DropdownReply', 'index_dropdownReply', $viewpParams);

        return $this->message('index');
    }



    public function actionAdd(ParameterBag $params)
    {


        $viewpParams = [
            'thread_id' => $params['thread_id']
        ];
     
        if (\XF::visitor()->is_admin || \XF::visitor()->is_moderator) {
            return $this->view('FS\DropdownReply', 'addEdit_dropdownReply', $viewpParams);
        } else {
            return $this->message('You are not allowed to add this');
        }
    }


    public function actionSave()
    {
        $input = [];

        $input = $this->filter([

            'status' => 'int',
            'options' => 'array',
            'thread_id' => 'int',

        ]);
        
        if (!count(array_filter($input['options']))){
           return $this->error('Please add at least one option');
        }
    $thread = $this->Finder('XF:Thread')->whereId($input['thread_id'])->fetchOne();
;
        $thread->bulkSet([
            'dropdwon_options'=> $input['options'],
            'is_dropdown_active'=> $input['status'],
        ]);
       $thread->save();

        return $this->redirect($this->buildLink('dropdownreply/'.$input['thread_id']), 'Dropdown Reply Added Successfully.', 'permanent');
        return $this->message('Save');
    }

    public function actionEdit(ParameterBag $params)
    {
        if ($params->thread_id){
            $thread = $this->Finder('XF:Thread')->where('thread_id',$params->thread_id)->fetchOne();

            $viewParams = ['thread'=>$thread];       
            if (\XF::visitor()->is_admin || \XF::visitor()->is_moderator) {
                return $this->view('FS\DropdownReply', 'addEdit_dropdownReply', $viewParams);
            } else {
                return $this->message('You are not allowed to add this');
            }
    
          
        return $this->message('Edit');
    }
}
    public function actionUpdate()
    {
        return $this->message('Update');
    }

    public function actionDeleteAll(ParameterBag $params)
    {

        $replyExists = $this->assertDataExists($params->thread_id);
       
        /* @var \XF\ControllerPlugin\Delete $plugin */
        $plugin = $this->plugin('XF:Delete');
        return $plugin->actionDelete(
            $replyExists,
            $this->buildLink('dropdownreply/delete', $replyExists),
            null,
            $this->buildLink('dropdownreply'),
            "Are you sure you want to delete",
        );
    }

    

    /**
   * @param string $id
   * @param array|string|null $with
   * @param null|string $phraseKey
   *
   * @return \CRUD\XF\Entity\Crud
   */
  protected function assertDataExists($id, array $extraWith = [], $phraseKey = null)
  {
      return $this->assertRecordExists('XF:Thread', $id, $extraWith, $phraseKey);
  }

  public function actionDelete(ParameterBag $params)
    {
        /**  @var \FS\ForumAutoReply\Entity\ForumAutoReply $replyExists */
        $replyExists = $this->assertDataExists($params->thread_id);

        /** @var \XF\ControllerPlugin\Delete $plugin */
        $plugin = $this->plugin('XF:Delete');

        if ($this->isPost()) {

            $this->preDeletehread($replyExists);

            return $this->redirect($this->buildLink('dropdownreply/'.$replyExists->thread_id));
        }

        return $plugin->actionDelete(
            $replyExists,
            $this->buildLink('dropdownreply/delete', $replyExists),
            $this->buildLink('dropdownreply/edit', $replyExists),
            $this->buildLink('dropdownreply'),
            \XF::phrase('fs_are_you_sure_to_delete')
        );
    }
  public function preDeletehread($thread){
    $thread->bulkSet([
        'dropdwon_options'=> null,
        'is_dropdown_active'=> 0,
    ]);
 

    $thread->save();
    

}
    }