<?php

namespace Snog\Forms\Repository;

use XF\Mvc\Entity\Repository;

class Form extends Repository
{
	public function deleteAllForms()
	{
		$this->db()->emptyTable('xf_snog_forms_forms');
	}

	/**
	 * @return \XF\Mvc\Entity\Finder|\Snog\Forms\Finder\Form
	 */
	public function findActiveFormsForList()
	{
		/** @var \XF\Mvc\Entity\Finder|\Snog\Forms\Finder\Form $formFinder */
		$formFinder = $this->finder('Snog\Forms:Form');
		return $formFinder->onlyActive()
			->applyTypeDefaultOrder();
	}

	/**
	 * @return \Snog\Forms\Entity\Form[]|\XF\Mvc\Entity\ArrayCollection
	 */
	public function getActiveFormTitlePairs()
	{
		return $this->findActiveFormsForList()->fetch()->pluckNamed('position', 'posid');
	}

	public function createFormTree($forms, $rootId = 0)
	{
		return new \XF\Tree($forms, 'display_parent', $rootId);
	}

	public function getReportTitle($title, array $titleAnswers, $reportSender, &$unansweredQuestionIds = [])
	{
		foreach ($titleAnswers as $key => $titleAnswer)
		{
			if (!empty($titleAnswer))
			{
				// ACCOUNT FOR POSSIBILITY THAT {A} CAN BE IN ANSWER
				if (stristr($titleAnswer, '{A') !== false)
				{
					$titleAnswer = str_replace('{A', '{ A', $titleAnswer);
				}

				$title = str_ireplace('{A' . $key . '}', $titleAnswer, $title);
			}
		}

		if (!($reportSender instanceof \XF\Entity\User))
		{
			$reportSender = \XF::visitor();
		}

		$title = strtr($title, [
			'{1}' => $reportSender->username,
			'{username}' => $reportSender->username,
			'{user_id}' => $reportSender->user_id,
			'{email}' => $reportSender->email,
		]);

		$unansweredQuestionIds = [];
		preg_match_all('/({A\d+})/', $title, $titleMisses);

		if (!empty($titleMisses[1]))
		{
			foreach ($titleMisses[1] as $titleMiss)
			{
				$questionNumber = str_replace('{A', '', $titleMiss);
				$questionNumber = str_replace('}', '', $questionNumber);
				$unansweredQuestionIds[] = $questionNumber;
			}
		}

		return $title;
	}
}
