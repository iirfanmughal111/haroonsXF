<?php

namespace XenBulletins\BrandHub\Admin\Controller;

use XF\Admin\Controller\AbstractField;
//use \XF\Entity\AbstractField as AbField;
use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class ItemField extends AbstractField {

    protected function getClassIdentifier() {
        return 'XenBulletins\BrandHub:ItemField';
    }

    protected function getLinkPrefix() {
        return 'item-field/fields';
    }

    protected function getTemplatePrefix() {
        return 'bh_Item_field';
    }
    
    

}
