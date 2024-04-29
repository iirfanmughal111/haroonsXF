<?php

namespace  FS\ThreadSaleItem\Service;

class SaleItem extends \XF\Service\AbstractService {

    
  
   public function allowsaleItem($node=null,$nodeId=null){
       
       
          if(isset($node['node_id']) && !$nodeId){
            
              $nodeId=$node->node_id;
              
          }elseif(isset($node['node_name']) && !$nodeId){
             
              $nodeName=$this->finder('XF:Node')->where('node_name',$node->node_name)->fetchOne();
 
              if($nodeName){
                  
                  $nodeId=$nodeName->node_id;
              }
              
          }elseif(!$node && $nodeId){
              
              $nodeId=$nodeId;
          }
          
         

        $saleItemForums=\XF::options()->fs_saleforums_allow;
         
        if(isset($saleItemForums) && count($saleItemForums)){
             
            $saleItemForums=array_filter($saleItemForums);
            
            if(in_array($nodeId,$saleItemForums)){
                
                
            
                return true;
                
            }
            
            return false;
        }
   
        return false;    
   }
   
   
    
}
