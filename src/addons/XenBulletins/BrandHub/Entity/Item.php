<?php

namespace XenBulletins\BrandHub\Entity;
use XF\Entity\ReactionTrait;
use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\Entity;
use XF\Util\Arr;
use XF\Entity\BookmarkTrait;
use XF\Entity\LinkableInterface;

class Item extends Entity implements LinkableInterface{
    
    
    use BookmarkTrait,ReactionTrait;


    protected function canBookmarkContent(&$error = null)
	{

		return $this->isVisible();
	}
    
    public function getBreadcrumbs($includeSelf = true)
	{

         
          $breadcrumbs = $this->Brand ? $this->Brand->getBreadcrumbs() : [];

		if ($includeSelf)
		{
			$breadcrumbs[] = [
				'href' => $this->app()->router()->buildLink('bh_brands/item', $this),
				'value' =>$this->item_title
			];
		}

		return $breadcrumbs;
	}
        
        
           public function getContentUrl(bool $canonical = false, array $extraParams = [], $hash = null)
	{
          
		$route = $canonical ? 'canonical:bh_brands/item' : 'bh_brands/item';
                
		return $this->app()->router('public')->buildLink($route, $this, $extraParams, $hash);
	}

	public function getContentPublicRoute()
	{
		return 'bh_brands/item';
	}

	public function getContentTitle(string $context = '')
	{
            if($this->item_title){
                
              return $this->item_title;
                
            }
	}
        


      
        public function canReact(&$error = null)
	{
            return true;
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}

//		if ($this->message_state != 'visible')
//		{
//			return false;
//		}
//
//		if ($this->user_id == $visitor->user_id)
//		{
//			$error = \XF::phraseDeferred('reacting_to_your_own_content_is_considered_cheating');
//			return false;
//		}
//
//		if (!$this->Thread)
//		{
//			return false;
//		}
//
//		return $visitor->hasNodePermission($this->Thread->node_id, 'react');
	}
        
      
       public function getAttachmentConstraints() {

            $options = $this->app()->options();

            $extensions = [];
          //  if (in_array('image', $this->allowed_types)) {
                $extensions = array_merge($extensions, Arr::stringToArray($options->bh_ImageExtensions));
            //}

//            $size = $this->hasPermission('bh_maxFileSize');
//            $width = $this->hasPermission('bh_maxImageWidth');
//            $height = $this->hasPermission('bh_maxImageHeight');

            // Treat both 0 and -1 as unlimited
            return [
                'extensions' => $extensions,
//                'size' => ($size <= 0) ? PHP_INT_MAX : $size  1024  1024,
//                'width' => ($width <= 0) ? PHP_INT_MAX : $width,
//                'height' => ($height <= 0) ? PHP_INT_MAX : $height,
//                'count' => 100
            ];
//        }
    }
    


    
    
//    public function getAllowedTypes() {
//        if ($this->category_id && $this->Category) {
//            return $this->Category->allowed_types;
//        } else {
//            $allowedTypes = [];
//            if ($this->hasPermission('xfrb_addImage')) {
//                $allowedTypes[] = 'image';
//            }
//            if ($this->hasPermission('xfrb_addEmbed')) {
//                $allowedTypes[] = 'embed';
//            }
//            return $allowedTypes;
//        }
//    }
//    
     public function getCustomFields() {
         
        
        /* @var \XF\CustomField\DefinitionSet $fieldDefinitions */
        $fieldDefinitions = $this->app()->container('customFields.bhItemfield');

       
        
        return new \XF\CustomField\Set($fieldDefinitions, $this);
    }
    
    
     public function getCustomFieldsList() {

        if (!$this->getValue('custom_fields')) {
            // if they haven't set anything, we can bail out quickly
            return [];
        }
        /* @var \XF\CustomField\Set $fieldSet */
        $fieldSet = $this->custom_fields;
        $definitionSet = $fieldSet->getDefinitionSet()
                ->filterOnly($this->Category->field_cache)
                ->filterGroup('above_record')
                ->filterWithValue($fieldSet);


        $output = [];
        foreach ($definitionSet AS $fieldId => $definition) {



            $output[$fieldId] = $definition;
        }

        return $output;
    }
    
        public function getCustomFieldsValue() {
        return $this->getValue('custom_fields');
    }
    
        
    public function isVisible()
    {
            return ($this->item_state == 'visible');
    }
        
        
    	public function canRate(&$error = null)
	{
		if (!$this->isVisible())
		{
			return false;
		}

		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}

		if (!$visitor->hasPermission('bh_brand_hub','bh_rate_item'))
		{
			return false;
		}

		return true;
	}
        
        public function canViewDeletedContent()
	{
            $visitor = \XF::visitor();
            return $visitor->hasPermission('bh_brand_hub','viewDeleted');
	}
        

        
         public function getThumbnailUrl()
	{
             $attachmentData = $this->finder('XF:Attachment')->where('content_id', $this->item_id)->where('content_type', 'bh_item')->fetchOne();

		return $attachmentData->Data ? $attachmentData->Data->getThumbnailUrl() : '';
	}
        
        
        
      
    
        public static function getStructure(Structure $structure) {
            
        $structure->table = 'bh_item';
        $structure->shortName = 'XenBulletins\BrandHub:Item';
        $structure->contentType = 'bh_item';
        $structure->primaryKey = 'item_id';
        $structure->columns = [
            
            'item_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'brand_id' => ['type' => self::UINT, 'api' => true],
            'category_id' => ['type' => self::UINT, 'api' => true],
            'item_title' => ['type' => self::STR, 'required' => true, 'censor' => true, 'api' => true,
                'maxLength' => 100
            ],
            'make' => ['type' => self::STR, 'default' => '', 'censor' => true, 'api' => true, 'maxLength' => 100
            ],
            'model' => ['type' => self::STR, 'default' => '', 'censor' => true, 'api' => true, 'maxLength' => 100
            ],
            'custom_fields' => ['type' => self::JSON_ARRAY, 'default' => []],
            'item_state' => ['type' => self::STR, 'default' => 'visible',
                'allowedValues' => ['visible', 'moderated', 'deleted'], 'api' => true
            ],
            'discussion_count' => ['type' => self::UINT, 'default' => 0],
            'view_count' => ['type' => self::UINT, 'default' => 0],
            'rating_count' => ['type' => self::UINT, 'default' => 0],
            'rating_sum' => ['type' => self::UINT, 'default' => 0],
            'rating_avg' => ['type' => self::FLOAT, 'default' => 0],
            'rating_weighted' => ['type' => self::UINT, 'default' => 0],
            'review_count' => ['type' => self::UINT, 'default' => 0],
            'position' => ['type' => self::UINT, 'forced' => true, 'api' => true],
            'reaction_score' => ['type' => self::UINT, 'default' => 0],
            'reaction_users' => ['type' => self::JSON_ARRAY, 'default' => []],
            'reactions' => ['type' => self::JSON_ARRAY, 'default' => []],
            'user_id' => ['type' => self::UINT, 'required' => true, ],
            'create_date' => ['type' => self::UINT, 'default' => \XF::$time, 'api' => true],
            
        ];
           $structure->getters = [
            'allowed_types' => true,
            'field_cache' => true,
            'custom_fields' => true,
            'custom_fields_list' => true,
            'thumbnail_url' => ['getter' => 'getThumbnailUrl', 'cache' => false],

        ];
           
          
           
          $structure->relations = [
            'Category' => [
                            'entity' => 'XenBulletins\BrandHub:Category',
                            'type' => self::TO_ONE,
                            'conditions' => [
                                    ['category_id', '=', '$category_id']
                            ],
                    ],
              
              'Brand' => [
                            'entity' => 'XenBulletins\BrandHub:Brand',
                            'type' => self::TO_ONE,
                            'conditions' => [
                                    ['brand_id', '=', '$brand_id']
                            ],
                    ],
            
            
            'Description' => [
                            'entity' => 'XenBulletins\BrandHub:ItemDescription',
                            'type' => self::TO_ONE,
                            'conditions' => [['item_id', '=', '$item_id']],
                            'primary' => true
                    ],
              
               'Attachment' => [
                'entity' => 'XF:Attachment',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['content_type', '=', 'bh_item'],
                    ['content_id', '=', '$item_id']
                ]
            ],
              
              'User' => [
				'entity' => 'XF:User',
				'type' => self::TO_ONE,
				'conditions' => 'user_id',
				'primary' => true,
				'api' => true
			],
              
            'ItemRatings' => [
                              'entity' => 'XenBulletins\BrandHub:ItemRating',
                              'type' => self::TO_MANY,
                              'conditions' => 'item_id',
                              'key' => 'user_id'
                      ],
//              'Bookmarks' => [
//			'entity' => 'XF:BookmarkItem',
//			'type' => self::TO_ONE,
//			'conditions' => [
//				['content_type', '=', $structure->contentType],
//				['content_id', '=', '$' . $structure->primaryKey]
//			],
//			'key' => 'user_id',
//			'order' => 'bookmark_date'
//		],
              
        ];
          	
          
         static::addBookmarkableStructureElements($structure);
         static::addReactableStructureElements($structure);
        return $structure;
             
        }
    
    
    
}