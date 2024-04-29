<?php

namespace FS\NodeUrl\XF\Entity;

use XF\Mvc\Entity\Structure;

class Node extends XFCP_Node
{
    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->columns['node_url'] =  ['type' => self::STR, 'default' => null];

        return $structure;
    }
}
