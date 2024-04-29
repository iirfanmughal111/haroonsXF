<?php

namespace FS\ScheduledPosting\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class ScheduledPosting extends Entity
{

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'fs_schedule_posting';
        $structure->shortName = 'FS\ScheduledPosting:ScheduledPosting';
        $structure->contentType = 'fs_schedule_posting';
        $structure->primaryKey = 'sch_id';
        $structure->columns = [
            'sch_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'title' => ['type' => self::STR, 'default' => null],
            'schedule_start' => ['type' => self::UINT, 'default' => null],
            'schedule_end' => ['type' => self::UINT, 'default' => null],
            'posting_start' => ['type' => self::UINT, 'default' => null],
            'msg_ex' => ['type' => self::STR,  'default' => null],
            'location_ex' => ['type' => self::STR, 'default' => null ],
            'entries_per_two_min' => ['type' => self::STR, 'default' => null],
            'entries_left' => ['type' => self::UINT, 'default' => null],
            'entry_last_id' => ['type' => self::UINT, 'default' => null],
        ];

        $structure->relations = [];
        $structure->defaultWith = [];
        $structure->getters = [];
        $structure->behaviors = [];

        return $structure;
    }
    
   
    
    public function getPostStarting() {


     
        return  date('H:i', $this->posting_start);
       
        
      
    }
    public function getScheduleEnd() {


     
        return  date('H:i', $this->schedule_end);
       
        
      
    }
    public function getScheduleStart() {


     
        return  date('H:i', $this->schedule_start);
       
        
      
    }


    public function getAbstractedTemprorarySavePath($filename) {
    
        return sprintf('data://TemporaryFiles/'.$filename);
    }

    public function viewAbstractedTemprorarySavePath($filename,$canonical=false) {
    
        $path= sprintf('TemporaryFiles/'.$filename);

        return \XF::getRootDirectory() . '/data/' . $path;
        
    }

    public function getAbstractedCustomdocPath($extension,$type) {

        $sch_id = $this->sch_id;
        
  
    
        return sprintf('data://SchedulePosting/'.$type.'/%d/%d.'.$extension, floor($sch_id / 1000), $sch_id);
    }
    
    public function viewAbstractedCustomdocPath($type,$extension)
    {
      
        $sch_id = $this->sch_id;
        $path= sprintf('SchedulePosting/'.$type.'/%d/%d.'.$extension, floor($sch_id / 1000), $sch_id);
       
        return \XF::getRootDirectory() . '/data/' . $path;
       
        $path= sprintf(
			'ClientDocuments/%d/%d.'.$extension,
			$this->doc_id
		);
       
        return \XF::app()->applyExternalDataUrl($path, $canonical);
    }
    
    
}
