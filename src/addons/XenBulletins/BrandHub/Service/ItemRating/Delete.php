<?php

namespace XenBulletins\BrandHub\Service\ItemRating;

use XenBulletins\BrandHub\Entity\ItemRating;

class Delete extends \XF\Service\AbstractService
{
	
	protected $rating;

	/**
	 * @var \XF\Entity\User|null
	 */
	protected $user;

	protected $alert = false;
	protected $alertReason = '';

	public function __construct(\XF\App $app, ItemRating $rating)
	{
		parent::__construct($app);
		$this->rating = $rating;
	}

	public function getRating()
	{
		return $this->rating;
	}

	public function setUser(\XF\Entity\User $user = null)
	{
		$this->user = $user;
	}

	public function getUser()
	{
		return $this->user;
	}

	public function setSendAlert($alert, $reason = null)
	{
		$this->alert = (bool)$alert;
		if ($reason !== null)
		{
			$this->alertReason = $reason;
		}
	}

	public function delete($type, $reason = '')
	{
		$user = $this->user ?: \XF::visitor();
		$wasVisible = $this->rating->rating_state == 'visible';

		if ($type == 'soft')
		{
			$result = $this->rating->softDelete($reason, $user);
		}
		else
		{
			$result = $this->rating->delete();
		}

		if ($result && $wasVisible && $this->alert && $this->rating->user_id != $user->user_id)
		{
			
			$ratingRepo = $this->repository('XenBulletins\BrandHub:ItemRating');
			$ratingRepo->sendModeratorActionAlert($this->rating, 'delete', $this->alertReason);
		}

		return $result;
	}
}