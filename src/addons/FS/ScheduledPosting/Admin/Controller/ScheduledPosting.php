<?php

namespace FS\ScheduledPosting\Admin\Controller;

use XF\Pub\Controller\AbstractController;
use XF\Mvc\ParameterBag;
class ScheduledPosting extends AbstractController
{
    public function actionIndex()
    {
        
        $page = $this->filterPage();
        $perPage = 20;

        $schedules = $this->Finder('FS\ScheduledPosting:ScheduledPosting');

        $total = $schedules->total();
        $this->assertValidPage($page, $perPage, $total, 'schedule');
        $schedules->limitByPage($page, $perPage);

        $viewParams = [
            'schedules' => $schedules->order('sch_id', 'DESC')->fetch(),
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total
        ];
        return $this->view('FS\ScheduledPosting:Index', 'fs_scheduled_posting_index',$viewParams);
    }

    public function actionAdd()
    {

        $shedule = $this->em()->create('FS\ScheduledPosting:ScheduledPosting');
        return $this->actionAddEdit($shedule);

       
    }
    
    public function actionAddEdit($shedule){
        
        
           $viewParams = [
            'shedule' => $shedule
        ];
         return $this->view('FS\ScheduledPosting:Add', 'fs_scheduled_posting_addEdit', $viewParams);
    }
    
    public function actionEdit(ParameterBag $params) {
        $scheduledPosting = $this->assertFunctionExists($params->sch_id);

        return $this->actionAddEdit($scheduledPosting);
    }
    
    protected function SaveProcess(\FS\ScheduledPosting\Entity\ScheduledPosting $shedule) {
        $form = $this->formAction();

        $input = $this->filter([
            'title' => 'str',
            'schedule_start' => 'str',
            'sch_starttime'=>'str',
            'schedule_end' => 'str',
            'sch_endtime'=>'str',
            'posting_start' => 'str',
            'sch_str_time'=>'str',
        
        ]);
         
        $message = $this->request->getFile('message_file', false, false);
        
        $location = $this->request->getFile('location_file', false, false);
        
   
        $input['schedule_start']=$this->AddDateTime($input['schedule_start'],$input['sch_starttime']);
        $input['schedule_end']=$this->AddDateTime($input['schedule_end'],$input['sch_endtime']);
        $input['posting_start']=$this->AddDateTime($input['posting_start'],$input['sch_str_time']);
         
        $threadWrite = $this->service('FS\ScheduledPosting:ThreadWrite');
    
    if($shedule->isInsert()){

        if(!$message || !$location){

            throw new \XF\PrintableException(\XF::phrase('all_field_required'));
        }
    }
    if($message){
         $input['msg_ex']=$message->getExtension();

        
         $dataFile = $shedule->getAbstractedTemprorarySavePath("message.".$message->getExtension());

          \XF\Util\File::copyFileToAbstractedPath($message->getTempFile(), $dataFile);
       
          $threadWrite->SetMessageRows("message.".$message->getExtension(),$shedule);
          \XF\Util\File::deleteFromAbstractedPath($dataFile);
        }
    if($location){

        $input['location_ex']= $location->getExtension();

        $dataFile = $shedule->getAbstractedTemprorarySavePath("location.".$location->getExtension());

        \XF\Util\File::copyFileToAbstractedPath($location->getTempFile(), $dataFile);
      
        $threadWrite->SetLocationRows("location.".$location->getExtension(),$shedule);

        \XF\Util\File::deleteFromAbstractedPath($dataFile);
    }  

        unset($input['sch_starttime']);
        unset($input['sch_endtime']);
        unset($input['sch_str_time']); 
       
      
        
    if($message && $location){

       list($entries_per_two_min,$totalEntries)=$threadWrite->checkwindowTime($input['schedule_start'],$input['schedule_end'],$shedule);
		

       $input['entries_per_two_min']=$entries_per_two_min;

      

       $input['entries_left']=$totalEntries;
    }
     
    if($shedule->isUpdate()){

        $entries_per_two_min=$shedule->entries_per_two_min;
      
        if(($message || $location) && $entries_per_two_min==(int) $entries_per_two_min){

            $input['entry_last_id']=$shedule->entry_last_id;
        }
       
    }
     
       $sheduleName = $this->actionFind($shedule->title);

       	
		if (!$sheduleName) 
		{


		
			$form->basicEntitySave($shedule,$input);

			return $form;
		}
		else
		{
			if ($shedule->sch_id == $sheduleName->sch_id) 
			{
				$form->basicEntitySave($shedule,$input);

				return $form;
			}
			else
			{
				$phraseKey = $sheduleName->title." function already exists.";
		 		throw $this->exception(
					$this->notFound(\XF::phrase($phraseKey))
				);
			}
		}

	    return $form;
         
         
    }
    
    public function AddDateTime($date,$time){
        

        $dateTime = new \DateTime(($date));

        list($hours, $minutes) = explode(':', $time);


        $dateTime->setTime($hours, $minutes);

        
        $timestamp = $dateTime->getTimestamp();
        
    
        return $timestamp;
    }
    
    
    public function actionFind($title)
   {
   
   
    return  $this->finder('FS\ScheduledPosting:ScheduledPosting')->where('title',$title)->fetchOne();
      

   }
    

    public function actionSave(ParameterBag $params)
    {
 
       

        
        $this->assertPostOnly();


        if ($params->sch_id) {
            $scheduledPosting = $this->assertFunctionExists($params->sch_id);
        } else {
            $scheduledPosting = $this->em()->create('FS\ScheduledPosting:ScheduledPosting');
        }

 
        $this->SaveProcess($scheduledPosting)->run();
      
      
         
        $message = $this->request->getFile('message_file', false, false);
        
        $location = $this->request->getFile('location_file', false, false);
        

        $uploadService = $this->service('FS\ScheduledPosting:Upload',$scheduledPosting);

        
        if ($message && !$uploadService->setDocFromUpload($message)) {
                    return $this->error($uploadService->getError());
                }
        if ($message && !$uploadService->uploadDocument($message,"message")) {
            return $this->error($uploadService->getError());
        }
        
  
        
        if ($location && !$uploadService->setDocFromUpload($location)) {
                    return $this->error($uploadService->getError());
                }
        if ($location && !$uploadService->uploadDocument($location,"location")) {
            return $this->error($uploadService->getError());
        }


     

      

        return $this->redirect($this->buildLink('schedule'));
    }

      protected function assertFunctionExists($id, $with = null, $phraseKey = null)
   {
       return $this->assertRecordExists('FS\ScheduledPosting:ScheduledPosting', $id, $with, $phraseKey);
   }

   public function actionDelete(ParameterBag $params) {

    $schedule = $this->assertFunctionExists($params->sch_id);

    /** @var \XF\ControllerPlugin\Delete $plugin */
    $plugin = $this->plugin('XF:Delete');

    return $plugin->actionDelete(
                    $schedule,
                    $this->buildLink('schedule/delete', $schedule),
                    $this->buildLink('schedule/edit', $schedule),
                    $this->buildLink('schedule'),
                    $schedule->title
    );
}
   

  
}
