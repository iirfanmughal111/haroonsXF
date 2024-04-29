<?php

namespace FS\DeleteEmail\XF\Entity;

use XF\Mvc\Entity\Structure;

class User extends XFCP_User
{
    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->columns['deleted_by'] =  ['type' => self::UINT, 'default' => 0];

        return $structure;
    }

    protected function _postSave()
    {
        parent::_postSave();

        if ($this->isUpdate() && $this->isChanged('email') && $this->email == '') {
            $visitor = \XF::visitor();
            if ($visitor->is_admin) {
                $this->fastUpdate('deleted_by', 1);
            }
        } else {
            $this->fastUpdate('deleted_by', 0);
        }
    }
}
