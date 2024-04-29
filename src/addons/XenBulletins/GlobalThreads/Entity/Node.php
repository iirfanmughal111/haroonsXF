<?php

namespace XenBulletins\GlobalThreads\Entity;

use XF\Mvc\Entity\Structure;

class Node extends XFCP_Node {

    public static function getStructure(Structure $structure) {
        $structure = parent::getStructure($structure);
        $structure->columns += [
            'g_thread_ids' => ['type' => self::LIST_COMMA, 'default' => [],
                'list' => ['type' => 'posint', 'unique' => true]
            ],
        ];
        $structure->getters += [
            'global_threads' => true,
        ];

        return $structure;
    }

    public function getGlobalThreads() {
        return is_array($this->g_thread_ids) ? implode(',', $this->g_thread_ids) : null;
    }

}
