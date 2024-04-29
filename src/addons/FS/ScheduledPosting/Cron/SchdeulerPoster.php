<?php

namespace FS\ScheduledPosting\Cron;



class SchdeulerPoster{

    public static function runAfterFiveMin() {


         $ThreadWrite = \xf::app()->service('FS\ScheduledPosting:ThreadWrite');

         $Schedules=$ThreadWrite->Schedules();

      
     
         
         if($Schedules){

            foreach($Schedules as $schedule){

              

                $MessageFile=$schedule->viewAbstractedCustomdocPath("message",$schedule->msg_ex);

                $locationFile=$schedule->viewAbstractedCustomdocPath("location",$schedule->location_ex);

             
               $messageData=$ThreadWrite->getSheetData($MessageFile);
            
       
          $messageKey=$ThreadWrite->messageKey($messageData);
            
       
         foreach($messageData as $key=>$data){
                
                 if ($key==0) continue;
                 
                  $message=$ThreadWrite->getmessage($data,$messageKey);
                
                    $WordInBraces=$ThreadWrite->getWordInCurly($message);
                    $locationrecords=$ThreadWrite->getSheetData($locationFile);

                
                //    foreach($WordInBraces as $key=>$wordinVrace){
                    
                    $stringInMessage="";
              
                    $stringInMessage=$locationrecords[0];
                
                    $user=\xf::app()->finder('XF:User')->where('user_id',\xf::options()->thread_creater_user ? \xf::options()->thread_creater_user : 1 )->fetchOne();
                    
                   
                    list($start,$end)=$ThreadWrite->startEndLoop($schedule);

                
                   
                    
                   
                    if(!$start || !$end){
                       
                        continue;
                    }

                         $count=5;
                        for($i=$start; $i<=$end; $i++){
                     
                            if($i!=0){
                           
                              
                                $message=$ThreadWrite->makeMessage($stringInMessage, $locationrecords[$i],$message);
                            
                                $thread=$ThreadWrite->makeThread($message,$data);   
                                static::getScheduleRepo()->schedule($thread,$user,$schedule->posting_start+$count);
                            }

                            
                          
                            

                            $count=$count+5;
                                
                                
                            }
                              
                            $ThreadWrite->MinusMinutesTime($schedule);

                        
                          
                            
                        }
                        
              
                       

                        if(!$schedule->entries_left){

                            \XF\Util\File::deleteFromAbstractedPath($MessageFile);
                            \XF\Util\File::deleteFromAbstractedPath($locationFile);

                        }
           }

        }
        
        
           

      
    }
    
   protected static function getScheduleRepo()
    {
        return \xf::app()->repository('BS\ScheduledPosting:Schedule');
    }

}
