<?php


namespace Snog\Forms\Repository;


use XF\Mvc\Entity\Repository;

class Log extends Repository
{
	/**
	 * @return \XF\Mvc\Entity\Finder|\Snog\Forms\Finder\Log
	 */
	public function findLogs()
	{
		return $this->finder('Snog\Forms:Log')
			->setDefaultOrder('log_id', 'DESC');
	}

	public function getCooldownStartDate(\Snog\Forms\Entity\Form $form, \XF\Entity\User $user, $ipAddress)
	{
		if ($form->cooldown === 0)
		{
			return 0;
		}

		$logFinder = $this->findLogs()
			->byUser($user);

		if (!$user->user_id)
		{
			$logFinder->byIp($ipAddress);
		}

		if ($form->cooldown !== -1)
		{
			$logFinder->newerThan(\XF::$time - $form->cooldown);
		}

		$logFinder->orderByDate();

		/** @var \Snog\Forms\Entity\Log $log */
		$log = $logFinder->fetchOne();

		return $log ? $log->log_date : 0;
	}

	/**
	 * @param \Snog\Forms\Entity\Form $form
	 * @param \XF\Entity\User|null $user
	 * @param null $ipAddress
	 * @return \Snog\Forms\Entity\Log
	 * @throws \XF\PrintableException
	 */
	public function createLog(\Snog\Forms\Entity\Form $form, \XF\Entity\User $user = null, $ipAddress = null)
	{
		if (!$user)
		{
			$user = \XF::visitor();
		}

		if (!$ipAddress)
		{
			$ipAddress = $this->app()->request()->getIp();
		}

		/** @var \Snog\Forms\Entity\Log $log */
		$log = $this->app()->em()->create('Snog\Forms:Log');
		$log->ip_address = \XF\Util\Ip::convertIpStringToBinary($ipAddress);
		$log->form_id = $form->posid;
		$log->user_id = $user->user_id;
		$log->save();

		return $log;
	}

	public function pruneLogs($cutOff = null)
	{
		if ($cutOff === null)
		{
			$cutOff = \XF::$time - 86400 * 30;
		}

		return $this->db()->delete('xf_snog_forms_logs', 'log_date < ?', $cutOff);
	}

	public function clearLog()
	{
		$this->db()->emptyTable('xf_snog_forms_logs');
	}
}