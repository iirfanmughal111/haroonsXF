<?php

namespace XenBulletins\BrandHub\ControllerPlugin;

use XF\ControllerPlugin\AbstractPlugin;
use XF\Mvc\Entity\ArrayCollection;
use XenBulletins\BrandHub\Entity\Item;
use XF\Entity\Attachment;
class FilmStrip extends AbstractPlugin
{
	protected $filmStripLimit = 7;

	protected function getDefaultOffset()
	{
		return floor($this->filmStripLimit / 2);
	}

	public function getFilmStripParamsForView(Attachment $itemAttachment,$page=null)
	{
            
          
//		$limitDays = $this->options()->xfmgMediaIndexLimit;



		$limit = $this->filmStripLimit;
            
               
		$offset = $this->getDefaultOffset();
      
		$position = $this->getPositionInContainer($itemAttachment,$page);
     
              
		$finder = $this->getMediaFinderFromPosition($itemAttachment, $position, 2, -$offset - 1,$page);
              
      
		$Items = $finder->fetch();
                
           
                
		$Itemsids = $Items->keys();

                 
               
		$hasPrev = false;
		$hasNext = false;


		$selectedIndex = array_search($itemAttachment->attachment_id, $Itemsids);
        
              
		if ($selectedIndex > 3)
		{
			$hasPrev = true;

			$Itemsids = $Items->keys();
		}

		if ($Items->count() > $limit)
		{
			$hasNext = true;
			$Items = $Items->slice(0, $limit);

			$Itemsids = $Items->keys();
                      
		}

		list($prevItem, $nextItem) = $this->getPrevAndNextItems($Items, $selectedIndex, $Itemsids);

		return [
			'Items' => $Items,
			'firstItem' => $Items->first(),
			'lastItem' => $Items->last(),
			'hasPrev' => $hasPrev,
			'hasNext' => $hasNext,
			'prevItem' => $prevItem,
			'nextItem' => $nextItem,
			'prevPlaceholders' => 0,
			'nextPlaceholders' => 0
		];
	}

	public function getFilmStripParamsForJump(Attachment $jumpFrom, $direction, $itemPage = null)
	{
		$limit = $this->filmStripLimit;

		$position = $this->getPositionInContainer($jumpFrom, $itemPage);
                
               
 

		$hasPrev = false;
		$hasNext = false;

		if ($direction == 'prev')
		{
			$limitExtra = 1;
			$offsetExtra = -$limit - 1;
			$hasNext = true;
		}
		else
		{
			$limitExtra = 2;
			$offsetExtra = 0;
			$hasPrev = true;
		}
                


		$finder = $this->getMediaFinderFromPosition($jumpFrom, $position, $limitExtra, $offsetExtra, $itemPage);
//
		$mediaItems = $finder->fetch();
      
		if ($direction == 'prev')
		{
			$mediaIds = $mediaItems->keys();

			$jumpFromIndex = array_search($jumpFrom->attachment_id, $mediaIds);
			if ($jumpFromIndex)
			{
				$mediaItems = $mediaItems->slice(0, $jumpFromIndex);
			}

			if ($mediaItems->count() > $limit)
			{
				$hasPrev = true;
				$mediaItems = $mediaItems->slice(1);
			}
		}
		else
		{
			$mediaItems = $mediaItems->slice(1);

			if ($mediaItems->count() > $limit)
			{
				$hasNext = true;
				$mediaItems = $mediaItems->slice(0, $limit);
			}
		}

		return [
			'Items' => $mediaItems,
			'firstItem' => $mediaItems->first(),
			'lastItem' => $mediaItems->last(),
			'hasPrev' => $hasPrev,
			'hasNext' => $hasNext,
			'prevPlaceholders' => 0,
			'nextPlaceholders' => 0
		];
	}

	public function getPositionInContainer(Attachment $Item_Attachment, $page=null)
	{
    
		$itemRepo = $this->repository('XenBulletins\BrandHub:Item');
           
 

		$allowOwnPending = is_callable([$this->controller, 'hasContentPendingApproval'])
			? $this->controller->hasContentPendingApproval()
			: true;

		$limits = ['allowOwnPending' => $allowOwnPending];

		if ($Item_Attachment->content_id)
		{
                    
                    if($page)
                    {
                        return $itemRepo->getCurrentPositionInAlbum($Item_Attachment,  $Item_Attachment->content_id, $limits, $page);
                    }
                    else
                    {
			return $itemRepo->getCurrentPositionInAlbum($Item_Attachment,  $Item_Attachment->content_id, $limits);
                    }
		}
		
	}


	public function getMediaFinderFromPosition(Attachment $Item_Attachment, $position, $limitExtra = 0, $offsetExtra = 0, $page = null)
	{
	
		$itemRepo = $this->repository('XenBulletins\BrandHub:Item');

		$limit = $this->filmStripLimit + $limitExtra;
		$filmStripOffset = $position + $offsetExtra;

		$allowOwnPending = is_callable([$this->controller, 'hasContentPendingApproval'])
			? $this->controller->hasContentPendingApproval()
			: true;
		$limits = ['allowOwnPending' => $allowOwnPending];

                
		if ($Item_Attachment->content_id)
		{
                   
        
			$finder = $itemRepo->findMediaForAlbum($Item_Attachment->content_id, $limits,$Item_Attachment->content_type ,$page);
		}
		

		return $finder->limit($limit, $filmStripOffset);
	}
        
        
	public function getPrevAndNextItems(ArrayCollection $mediaItems, $selectedIndex, array $mediaIds)
	{
		$prevItemId = isset($mediaIds[$selectedIndex - 1]) ? $mediaIds[$selectedIndex - 1] : null;
		$nextItemId = isset($mediaIds[$selectedIndex + 1]) ? $mediaIds[$selectedIndex + 1] : null;

		if ($prevItemId)
		{
			$prevItem = $mediaItems[$prevItemId];
		}
		else
		{
			$prevItem = null;
		}
		if ($nextItemId)
		{
			$nextItem = $mediaItems[$nextItemId];
		}
		else
		{
			$nextItem = null;
		}

		return [$prevItem, $nextItem];
	}

	public function getPrevAndNextPlaceholders(ArrayCollection &$mediaItems, $selectedIndex = 0)
	{
		$limit = $this->filmStripLimit;
		$offset = $this->getDefaultOffset();

		$prevPlaceholders = $offset - $selectedIndex;
		$nextPlaceholders = 0;

		if ($prevPlaceholders)
		{
			$mediaItems = $mediaItems->slice(0, $limit - $prevPlaceholders);
		}

		$count = $mediaItems->count();

		if ($count < $limit + $prevPlaceholders)
		{
			$nextPlaceholders = $limit - $prevPlaceholders - $count;
		}

		return [$prevPlaceholders, $nextPlaceholders];
	}
}