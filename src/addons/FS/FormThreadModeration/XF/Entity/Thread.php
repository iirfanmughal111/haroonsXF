<?php

namespace FS\FormThreadModeration\XF\Entity;

use XF\Mvc\Entity\Structure;

class Thread extends XFCP_Thread
{

    public function isApplicableFormThread()
    {
        $optionNodeIds = \xf::options()->node_ids;

        if ($optionNodeIds && in_array($this->node_id, $optionNodeIds)) {
            return true;
        }

        return false;
    }

    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->columns['parent_thread_id'] =  ['type' => self::UINT, 'nullable' => true, 'default' => null];

        return $structure;
    }

    public function getParentThread()
    {
        if ($this->parent_thread_id) {
            $parentThread = $this->em()->findOne('XF:Thread', ['thread_id', '=', $this->parent_thread_id]);

            return $parentThread;
        }
    }
}
