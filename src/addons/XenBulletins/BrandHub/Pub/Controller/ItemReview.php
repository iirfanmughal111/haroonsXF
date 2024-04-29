<?php

namespace XenBulletins\BrandHub\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;

class ItemReview extends AbstractController
{


	public function actionIndex(ParameterBag $params)
	{
		$review = $this->assertViewableReview($params->item_rating_id);

		return $this->redirectToReview($review);
	}

	public function actionDelete(ParameterBag $params)
	{
		$review = $this->assertViewableReview($params->item_rating_id);
                $ratingState = $review->rating_state; 
                
		if (!$review->canDelete('soft', $error))
		{
			return $this->noPermission($error);
		}

		if ($this->isPost())
		{
                
			$type = $this->filter('hard_delete', 'bool') ? 'hard' : 'soft';
			$reason = $this->filter('reason', 'str');
                       
			if (!$review->canDelete($type, $error))
			{
				return $this->noPermission($error);
			}

			$deleter = $this->service('XenBulletins\BrandHub:ItemRating\Delete', $review);

			if ($this->filter('author_alert', 'bool') && $review->canSendModeratorActionAlert())
			{
				$deleter->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
			}

			$deleter->delete($type, $reason);
                        
                        if($ratingState == 'visible')
                        {
                            \XenBulletins\BrandHub\Helper::updateRatingAndReviewCount($review, 'minus');
                        }
                        
                      
                        $review = $this->em()->find('XenBulletins\BrandHub:ItemRating', $params->item_rating_id);
                        if (!$review)
                        {
                            return $this->redirect($this->buildLink('bh_brands/item/'.$params->item_id.'/#reviews'));
                        }
                        
                        
			return $this->redirect(
				$this->buildLink('bh_brands/review', $review)
			);
		}
		else
		{
			$viewParams = [
				'review' => $review,
				'item' => $review->Item
			];
			return $this->view('XenBulletins\BrandHub:ItemReview\Delete', 'bh_delete_review', $viewParams);
		}
	}

	public function actionUndelete(ParameterBag $params)
	{ 
		$review = $this->assertViewableReview($params->item_rating_id);
                $ratingState = $review->rating_state;
                
                if(!$review->canUndelete())
                {
                    return $this->noPermission($error);
                }

              
                if($this->isPost() && ($ratingState == 'deleted'))
                {
                    $review->rating_state = 'visible';
                    $review->save();
                    
                    \XenBulletins\BrandHub\Helper::updateRatingAndReviewCount($review, 'plus');
                }
		/** @var \XF\ControllerPlugin\Undelete $plugin */
		$plugin = $this->plugin('XF:Undelete');
		return $plugin->actionUndelete(
			$review,
			$this->buildLink('bh_brands/review/undelete', $review),
			$this->buildLink('bh_brands/review', $review),
			\XF::phrase('bh_review_in_x_item', ['itemTitle' => $review->Item->item_title]),
			'rating_state'
		);
	}

	protected function redirectToReview(\XenBulletins\BrandHub\Entity\ItemRating $review)
	{
		$item = $review->Item;

		$newerFinder = $this->getRatingRepo()->findReviewsInItem($item);
		$newerFinder->where('rating_date', '>', $review->rating_date);
		$totalNewer = $newerFinder->total();

		$perPage = $this->options()->bh_ReviewsPerPage;
		$page = ceil(($totalNewer + 1) / $perPage);

		if ($page > 1)
		{
			$params = ['page' => $page];
		}
		else
		{
			$params = [];
		}

		return $this->redirect(
			$this->buildLink('bh_brands/item/reviews', $item, $params)
			. '#item-review-' . $review->item_rating_id
		);
	}

        
        
	protected function assertViewableReview($itemRatingId, array $extraWith = [])
	{
		$visitor = \XF::visitor();

		$extraWith[] = 'Item';

		$review = $this->em()->find('XenBulletins\BrandHub:ItemRating', $itemRatingId, $extraWith);
		if (!$review)
		{
			throw $this->exception($this->notFound(\XF::phrase('bh_requested_review_not_found')));
		}

		if (!$review->is_review)
		{
			throw $this->exception($this->noPermission($error));
		}


		return $review;
	}

	protected function getRatingRepo()
	{
		return $this->repository('XenBulletins\BrandHub:ItemRating');
	}

	public static function getActivityDetails(array $activities)
	{
		return \XF::phrase('xfrm_viewing_resources');
	}
}