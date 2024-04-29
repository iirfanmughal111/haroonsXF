<?php

namespace FS\NodeIcon\XF\Entity;

use XF\Mvc\Entity\Structure;

class Node extends XFCP_Node
{
    public function getIcon()
    {
        $icon = 'data://nodeIcons/' . $this->node_id . '.jpg';

        if (\XF\Util\File::abstractedPathExists($icon)) {
            return $this->app()->applyExternalDataUrl('nodeIcons/' . $this->node_id . '.jpg?' . $this->icon_time, true);
        }

        return;
    }

    protected function _preSave()
    {
        $parent = parent::_preSave();

        $this->icon_time = \XF::$time;

        return $parent;
    }

    protected function _postSave()
    {
        if ($upload = \xf::app()->request->getFile('upload', false, false)) {
            \xf::app()->repository('FS\NodeIcon:Node')->setIconFromUpload($this, $upload);
        }
    }

    protected function _postDelete()
    {
        $parent = parent::_postDelete();

        \XF\Util\File::deleteFromAbstractedPath('data://nodeIcons/' . $this->node_id . '.jpg');

        return $parent;
    }

    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->columns['icon_time'] = ['type' => self::INT, 'default' => 0];

        return $structure;
    }
}
