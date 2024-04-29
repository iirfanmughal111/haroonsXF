<?php

namespace FS\ScheduledPosting\Service;

require __DIR__ . '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ThreadWrite extends \XF\Service\AbstractService {

    public $messageRows='';
    public $locationRows='';
    public function getSheetData($filePath,$detail=null) {

        $inputFileType = 'Xlsx';
        $inputFileName = $filePath;

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);

        $reader->setReadDataOnly(true);

        $worksheetData = $reader->listWorksheetInfo($inputFileName);

        if($detail){
            
            return $worksheetData;
        }
        $sheetName = $worksheetData[0]['worksheetName'];

        $reader->setLoadSheetsOnly($sheetName);
        $spreadsheet = $reader->load($inputFileName);

        $worksheet = $spreadsheet->getActiveSheet();
        $array = $worksheet->toArray();
        
        return $array;
    }
    
    
    
    public function messageKey($data){
        
         $firstRowArray=$data[0];
         
         if(count($firstRowArray)){
             
             return  array_search("message",$firstRowArray,true);
         }
       
        
    }
    
    
    public function makeMessage($stringInMessage,$stringInlocation,$message){
        
        $message=str_replace($stringInMessage, $stringInlocation, $message);
        
        $message=preg_replace('/[{}]/', '', $message);
        
        return $message;
    }
    
     public function wordKeyFromLoction($data,$find){
        
         $firstRowArray=$data[0];
         
         if(count($firstRowArray)){
             
             return  array_search($find,$firstRowArray,true);
         }
       
        
    }
    
    public function getmessage($data,$key){
        
        
        return $data[$key];
      
        
    }

    public function getWordInCurly($message) {

        preg_match_all('/{(.*?)}/', $message, $matches);

        return $matches[1];
    }

    public function makeThread($message,$messageData,$user){
        
       $formId=$messageData[0];

        $title=$messageData[1];
     
        $forum = $this->assertViewableForum($formId);
        
        $creator = \XF::service('XF:Thread\Creator', $forum,$user);
        $creator->setContent($title, $message);
	$creator->setPerformValidations(false);
        $creator->setDiscussionState(
                "scheduled"
        );

        $thread = $creator->save();
        
        return $thread;
        

       
    }
    
    public function assertViewableForum($nodeId)
	{
        	return $this->finder('XF:Forum')->where('node_id', $nodeId)->fetchOne();
               
       }
    
    public function SetMessageRows($fileName,$schedule){


        $filePath=$schedule->viewAbstractedTemprorarySavePath($fileName,true);

        $message=$this->getSheetData($filePath,true);

        $this->messageRows=$message[0]['totalRows'];
        

    }

    public function SetLocationRows($fileName,$schedule){


        $filePath=$schedule->viewAbstractedTemprorarySavePath($fileName,true);

        $location=$this->getSheetData($filePath,true);

    
        $this->locationRows=$location[0]['totalRows'];
        

    }
       
    public function checkwindowTime($startDate,$endDate,$schedule){
           
      
      
        $hourdiff = round(($endDate-$startDate)/3600, 1);
        $totalEntries=(int) $this->locationRows* (int)$this->messageRows;
        $total=$totalEntries/2;
        $totalhours=$total/1000;

        $entry_per_two_mint=ceil($totalhours*30);



        if($hourdiff<$totalhours && $hourdiff!=$totalhours){

            throw new \XF\PrintableException(\XF::phrase('window_time_should_be_greater', ['hour' => round($totalhours)]));

         
        }

        $entry_per_two_mint= round($totalEntries/$entry_per_two_mint);
       return [$entry_per_two_mint,$totalEntries];

     
       
        
     
           
     }




     public function Schedules(){


        $schedules=$this->finder('FS\ScheduledPosting:ScheduledPosting')->where('schedule_start','<',\xf::$time)->where('schedule_end','>',\xf::$time)->where('entries_left','!=',0)->fetch();
       return count($schedules) ? $schedules : null;

     }

     public function startEndLoop($schedule){

        $entries_per_two_min=(int) $schedule->entries_per_two_min;
        $entries_left=(int) $schedule->entries_left;
        $entry_last_id=(int) $schedule->entry_last_id;

        if(!$entry_last_id){

            return [1,$entries_per_two_min];
        }

        $startloop=$entry_last_id-($entries_per_two_min+1);
        
        $endloop=$entry_last_id;
        return [$startloop,$endloop];

     }

     public function MinusMinutesTime($schedule){

        $entries_per_two_min=(int) $schedule->entries_per_two_min;
        $entries_left=(int) $schedule->entries_left;
        $entry_last_id=(int) $schedule->entry_last_id;

        if(!$entry_last_id){

            $schedule->fastUpdate('entry_last_id',$entries_per_two_min);

        }

        
        $schedule->fastUpdate('entry_last_id',$schedule->entry_last_id+($schedule->entries_per_two_min+1));

        $entries_left=$schedule->entries_left-$schedule->entries_per_two_min;

        if($entries_left<0){

            $schedule->fastUpdate('entries_left',0);

            return true;

        }
        $schedule->fastUpdate('entries_left',$entries_left);
    
      

     }
}