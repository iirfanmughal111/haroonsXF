<?php

namespace FS\ThreadSaleItem\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Forum extends XFCP_Forum
{
    
    public function actionPostThread(ParameterBag $params) {
        
        

       
        $parent=parent::actionPostThread($params);
        
       
        
              $saleItemService = $this->service('FS\ThreadSaleItem:SaleItem');
  
                
             
                if ($parent instanceof \XF\Mvc\Reply\View && \XF::visitor()->hasPermission('fs_threadsaleitem', 'fs_saleitem')) {


                    $parent->setParam('sale_item', $saleItemService->allowsaleItem($params,null));
                
                
                }
                
            
          
         
         return $parent;
         
        
    }
    
    protected function setupThreadCreate(\XF\Entity\Forum $forum) {
        
        $parent = parent::setupThreadCreate($forum);
    
         $saleItemService = $this->service('FS\ThreadSaleItem:SaleItem');
  

        $saleItem = $this->filter('sale_item', 'int');
      
        if($saleItem){
            
                if($saleItemService->allowsaleItem(null,$forum->node_id) && \XF::visitor()->hasPermission('fs_threadsaleitem', 'fs_saleitem')){

                    

                    $parent->setSaleItem($saleItem);
                }
        }
        return $parent;
    }
 
    
}
