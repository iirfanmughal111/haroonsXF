<?php


namespace Snog\Forms\Repository;


use XF\Mvc\Entity\Repository;

class Answer extends Repository
{
	/**
	 * @return \XF\Mvc\Entity\Finder|\Snog\Forms\Finder\Answers
	 */
	public function findAnswers()
	{
		return $this->finder('Snog\Forms:Answers')
			->setDefaultOrder('answer_id', 'ASC');
	}

	public function saveAnswers(array $storeAnswers, $formId = 0, $logId = 0, $userId = 0, $submitDate = 0)
	{
		if (empty($storeAnswers))
		{
			return 0;
		}

		if (!$submitDate)
		{
			$submitDate = \XF::$time;
		}

		foreach ($storeAnswers as $key => $storeAnswer)
		{
			$storeAnswers[$key]['log_id'] = $logId;
			$storeAnswers[$key]['posid'] = $formId;
			$storeAnswers[$key]['answer_date'] = $submitDate;
			$storeAnswers[$key]['user_id'] = $userId;
		}

		return $this->db()->insertBulk('xf_snog_forms_answers', $storeAnswers);
	}

	public function clearAnswers()
	{
		return $this->db()->emptyTable('xf_snog_forms_answers');
	}
}