<?php

namespace XenBulletins\BrandHub\Entity;


use XF\Entity\AbstractField;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property string field_id
 * @property int display_order
 * @property string field_type
 * @property array field_choices
 * @property string match_type
 * @property array match_params
 * @property int max_length
 * @property bool required
 * @property string display_template
 * @property string display_group
 * @property bool album_use
 * @property bool display_add_media
 *
 * GETTERS
 * @property \XF\Phrase title
 * @property \XF\Phrase description
 *
 * RELATIONS
 * @property \XF\Entity\Phrase MasterTitle
 * @property \XF\Entity\Phrase MasterDescription
 * @property \XF\Mvc\Entity\AbstractCollection|\XenBulletins\RecordBook\Entity\CategoryField[] CategoryFields
 */
class ItemField extends AbstractField
{
	protected function getClassIdentifier()
	{
		return 'XenBulletins\BrandHub:ItemField';
	}

	protected static function getPhrasePrefix()
	{
		return 'bh_Item_field';
	}

//	protected function _postDelete()
//	{
//		/** @var \XenBulletins\RecordBook\Repository\CategoryField $repo */
//		$repo = $this->repository('XenBulletins\RecordBook:CategoryField');
//		$repo->removeFieldAssociations($this);
//
//		$this->db()->delete('xf_rb_RecordBook_field_value', 'field_id = ?', $this->field_id);
//
//		parent::_postDelete();
//	}

//        public function getFieldChoices()
//	{
//            var_dump('sdfsd');exit;
//        }
        
        
        
        
        
	public static function getStructure(Structure $structure)
	{
		self::setupDefaultStructure(
			$structure,
			'bh_item_field',
			'XenBulletins\BrandHub:ItemField',
			[
				'groups' => ['below_record','above_record']
			]
		);
                
              //  $structure->columns['is_moderation_required']=['type' => self::UINT, 'default' => 0];
              //  $structure->getters['field_choices']=TRUE;    
                

		$structure->relations['CategoryFields'] = [
			'entity' => 'XenBulletins\BrandHub:CategoryField',
				'type' => self::TO_MANY,
				'conditions' => 'field_id'
		];

		return $structure;
	}
}