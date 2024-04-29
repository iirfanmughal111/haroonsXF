<?php

namespace FS\Fshome;

use XF\Util\Arr;

class Listener {

    public static function postAddonInstall(\XF\AddOn\AddOn $addOn, \XF\Entity\AddOn $installedAddOn, array $json, array &$stateChanges) {

        $app = \xf::app();

        $PastPage = $app->finder('XF:Node')->where('title', 'Fshome')->fetchOne();

        if($PastPage){
             
             $PastPage->delete();
         }
         
        $optionIndex = $app->finder('XF:Option')->where('option_id', 'indexRoute')->fetchOne();

        if ($optionIndex) {

            $optionIndex->option_value='pages/fshome/';
            $optionIndex->save();
          
        }
        $node = $app->em->create('XF:Node');
        $node->title = "Fshome";
        $node->node_name = "fshome";
        $node->node_type_id = "Page";
        $node->display_order = 1;
        $node->display_in_list = 1;
        $node->save();

        $template = $app->finder('XF:Template')->where('title', '_page_node.40')->fetchOne();

        if ($template) {
            
          $template->title='_page_node.' . $node->node_id;
        
          $template->save();
          
        }
        $page = $app->em->create('XF:Page');
        $page->node_id = $node->node_id;
        $page->save();
    }

}
