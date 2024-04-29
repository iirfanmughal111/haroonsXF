<?php

namespace XenBulletins\BrandHub\Service\Item;

use XenBulletins\BrandHub\Entity\Item;

class Rate extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	protected $item;

	
	protected $rating;

	protected $reviewRequired = false;
	protected $reviewMinLength = 0;

	protected $sendAlert = true;

	public function __construct(\XF\App $app, Item $item)
	{
		parent::__construct($app);

		$this->item = $item;
		$this->rating = $this->setupRating();

		$this->reviewRequired = $this->app->options()->bh_ReviewRequired;
		$this->reviewMinLength = $this->app->options()->bh_MinimumReviewLength;
	}

	protected function setupRating()
	{
		$item = $this->item;

		$rating = $this->em()->create('XenBulletins\BrandHub:ItemRating');
		$rating->item_id = $item->item_id;
		$rating->user_id = \XF::visitor()->user_id;

		return $rating;
	}

	public function getItem()
	{
		return $this->item;
	}

	public function getRating()
	{
		return $this->rating;
	}

	public function setRating($rating, $message = '')
	{
		$this->rating->rating = $rating;
		$this->rating->message = $message;
	}


	public function setReviewRequirements($reviewRequired = null, $minLength = null)
	{
		if ($reviewRequired !== null)
		{
			$this->reviewRequired = (bool)$reviewRequired;
		}
		if ($minLength !== null)
		{
			$minLength = max(0, intval($minLength));
			$this->reviewMinLength = $minLength;
		}
	}

	public function checkForSpam()
	{
		$rating = $this->rating;

		if (
			!\XF::visitor()->isSpamCheckRequired()
			|| !strlen($this->rating->message)
			|| $this->rating->getErrors()
		)
		{
			return;
		}

		/** @var \XF\Entity\User $user */
		$user = $rating->User;

		$message = $rating->message;

		$checker = $this->app->spam()->contentChecker();
		$checker->check($user, $message, [
			'permalink' => $this->app->router('public')->buildLink('canonical:bh_brands/item', $rating->Item),
			'content_type' => 'item_rating'
		]);

		$decision = $checker->getFinalDecision();
		switch ($decision)
		{
			case 'moderated':
			case 'denied':
				$checker->logSpamTrigger('item_rating', null);
				$rating->error(\XF::phrase('your_content_cannot_be_submitted_try_later'));
				break;
		}
	}

	protected function _validate()
	{
		$rating = $this->rating;

                
		$rating->preSave();
		$errors = $rating->getErrors();
                
               

		if ($this->reviewRequired && !$rating->is_review)
		{
			$errors['message'] = \XF::phrase('bh_please_provide_review_with_your_rating');
		}

		if ($rating->is_review && utf8_strlen($rating->message) < $this->reviewMinLength)
		{
			$errors['message'] = \XF::phrase(
				'bh_your_review_must_be_at_least_x_characters',
				['min' => $this->reviewMinLength]
			);
		}

		if (!$rating->rating)
		{
			$errors['rating'] = \XF::phrase('bh_please_select_star_rating');
		}

		return $errors;
	}

	protected function _save()
	{
		$rating = $this->rating;
                $item = $this->item;


		$existing = $this->item->ItemRatings[$rating->user_id];
		if ($existing)
		{
                    \XenBulletins\BrandHub\Helper::updateRatingAndReviewCount($rating, 'minus');
			$existing->delete();
		}
                
		$rating->save(true, false);
                
                
                \XenBulletins\BrandHub\Helper::updateRatingAndReviewCount($rating, 'plus');
                

//		if ($this->sendAlert)
//		{
//			$this->repository('XFRM:ResourceRating')->sendReviewAlertToResourceAuthor($rating);
//		}

		return $rating;
	}
}