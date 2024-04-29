<?php


namespace Snog\Forms\Finder;


use XF\Mvc\Entity\Finder;

class Log extends Finder
{
	public function byForm($formId)
	{
		if ($formId instanceof \Snog\Forms\Entity\Form)
		{
			$formId = $formId->getEntityId();
		}

		$this->where('form_id', '=', $formId);

		return $this;
	}

	public function byUser($userId)
	{
		if ($userId instanceof \XF\Entity\User)
		{
			$userId = $userId->getEntityId();
		}

		$this->where('user_id', '=', $userId);

		return $this;
	}

	public function byIp($ipAddress)
	{
		$this->where('user_id', '=', \XF\Util\Ip::convertIpStringToBinary($ipAddress));

		return $this;
	}

	public function olderThan($date)
	{
		$this->where('log_date', '<', $date);
		return $this;
	}

	public function newerThan($date)
	{
		$this->where('log_date', '>', $date);
		return $this;
	}

	public function orderByDate($direction = 'ASC')
	{
		$this->setDefaultOrder('log_date', $direction);
		return $this;
	}
}