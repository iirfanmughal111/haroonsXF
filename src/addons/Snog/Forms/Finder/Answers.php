<?php


namespace Snog\Forms\Finder;


use XF\Mvc\Entity\Finder;

class Answers extends Finder
{
	public function byForm($formId)
	{
		if ($formId instanceof \Snog\Forms\Entity\Form)
		{
			$formId = $formId->getEntityId();
		}

		$this->where('posid', '=', $formId);

		return $this;
	}

	public function byLog($logId)
	{
		if ($logId instanceof \Snog\Forms\Entity\Log)
		{
			$logId = $logId->getEntityId();
		}

		$this->where('log_id', '=', $logId);

		return $this;
	}
}