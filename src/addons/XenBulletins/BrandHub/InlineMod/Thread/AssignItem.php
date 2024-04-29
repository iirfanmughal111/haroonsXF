<?php

namespace XenBulletins\BrandHub\InlineMod\Thread;

use XF\Http\Request;
use XF\InlineMod\AbstractAction;
use XF\Mvc\Entity\AbstractCollection;
use XF\Mvc\Entity\Entity;

class AssignItem extends AbstractAction
{
	public function getTitle()
	{
		return \XF::phrase('bh_link_to_brand_hub');
	}

	protected function canApplyToEntity(Entity $entity, array $options, &$error = null)
	{
		/** @var \XF\Entity\Thread $entity */
		return $entity->canEdit($error);
	}

	protected function applyToEntity(Entity $entity, array $options)
	{
            $alreadyItemId = $entity->item_id;
            $itemId = $options['item_id'];
            
            if($itemId != $alreadyItemId)
            {
                  if(!$alreadyItemId && $itemId)
                  {
                      \XenBulletins\BrandHub\Helper::updateItemDiscussionCount($itemId,'plus');
                  }

                  else if($alreadyItemId && !$itemId)
                  {
                      \XenBulletins\BrandHub\Helper::updateItemDiscussionCount($alreadyItemId,'minus');
                  }

                  else if ($itemId && $alreadyItemId)
                  {
                      \XenBulletins\BrandHub\Helper::updateItemDiscussionCount($alreadyItemId,'minus');               
                      \XenBulletins\BrandHub\Helper::updateItemDiscussionCount($itemId,'plus');
                  }
            }

		if ($entity->discussion_type == 'redirect')
		{
			return;
		}


		/** @var \XF\Service\Thread\Editor $editor */
		$editor = $this->app()->service('XF:Thread\Editor', $entity);
		$editor->setPerformValidations(false);
                $entity->item_id = $options['item_id'];
		if ($editor->validate($errors))
		{
			$editor->save();
		}
                
	}

	public function getBaseOptions()
	{
		return [
			'prefix_id' => null
		];
	}

	public function renderForm(AbstractCollection $entities, \XF\Mvc\Controller $controller)
	{       
            $visitor = \xf::visitor();
            if(!$visitor->hasPermission('bh_brand_hub', 'bh_can_assignThreadsToHub'))
            {
                throw $this->exception($this->noPermission());
            }
		$forums = $entities->pluckNamed('Forum', 'node_id');

                if(count($forums) > 1)
                {
                    return $controller->error(\XF::phrase('bh_you_have_selected_threads_from_multiple_forums'));
                }
                
                if(!$forums->first()->brand_id)
                {
                    return $controller->error(\XF::phrase('bh_no_brand_assign_to_selected_forum'));
                }
                    
                $items = \XF::Finder('XenBulletins\BrandHub:Item')->where('brand_id', $forums->first()->brand_id)->fetch();

		if (!$items->count())
		{
			return $controller->error(\XF::phrase('bh_no_items_available_for_selected_brand'));
		}

		$selectedItem = 0;
		$itemCounts = [0 => 0];
                
		foreach ($entities AS $thread)
		{
			$threadItemId = $thread->item_id;

			if (!isset($itemCounts[$threadItemId]))
			{
				$itemCounts[$threadItemId] = 1;
			}
			else
			{
				$itemCounts[$threadItemId]++;
			}

			if ($itemCounts[$threadItemId] > $itemCounts[$selectedItem])
			{
				$selectedItem = $threadItemId;
			}
		}


		$viewParams = [
			'threads' => $entities,
			'items' => $items,
			'forumCount' => count($forums->keys()),
			'selectedItem' => $selectedItem,
			'total' => count($entities)
		];
		return $controller->view('XenBulletins\BrandHub:Public:InlineMod\Thread\AssignItem', 'bh_inline_mod_assignItem', $viewParams);
	}

	public function getFormOptions(AbstractCollection $entities, Request $request)
	{
		return [
			'item_id' => $request->filter('item_id', 'uint')
		];
	}
}