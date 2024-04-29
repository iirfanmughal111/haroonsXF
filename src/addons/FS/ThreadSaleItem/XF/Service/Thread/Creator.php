<?php

namespace FS\ThreadSaleItem\XF\Service\Thread;

class Creator extends XFCP_Creator {

    protected $sale_item;

    public function setSaleItem($saleItem){
       

        $this->thread->sale_item = $saleItem;
       

    }
  

}
