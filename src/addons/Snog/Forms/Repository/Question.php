<?php

namespace Snog\Forms\Repository;

use XF\Mvc\Entity\Repository;

class Question extends Repository
{
	/**
	 * @param int $posid
	 * @return \XF\Mvc\Entity\AbstractCollection|\Snog\Forms\Entity\Question[]
	 */
	public function getQuestionList($posid = 0)
	{
		return $this->finder('Snog\Forms:Question')
			->order('display', 'ASC')
			->where('posid', '=', $posid)
			->fetch();
	}

	public function createQuestionTree($questions, $rootId = 0)
	{
		return new \XF\Tree($questions, 'display_parent', $rootId);
	}

	public function getQuestionConditionals(\Snog\Forms\Entity\Question $question, $questions, &$uploadNeeded = false)
	{
		$conditionals = [];

		/** @var \Snog\Forms\Entity\Question $conditionQuestion */
		foreach ($questions as $conditionQuestion)
		{
			if (in_array($conditionQuestion->questionid, $question->hasconditional))
			{
				$conditionals[] = [
					'display' => $conditionQuestion->display,
					'answer' => $conditionQuestion->conanswer,
					'questionid' => $conditionQuestion->questionid
				];

				// Detect file upload conditional question type
				if ($conditionQuestion->isUploadNeeded())
				{
					$uploadNeeded = true;
				}
			}
		}

		// Put conditionals in display order
		$arrayColumn = array_column($conditionals, 'answer');
		array_multisort($arrayColumn, SORT_ASC,
			array_column($conditionals, 'display'), SORT_ASC,
			$conditionals);

		return $conditionals;
	}

	public function getFileUploadsAttachReply(\Snog\Forms\Entity\Question $question, $attachments)
	{
		$attachReply = '';

		if ($question->inline > 1)
		{
			$imageFound = false;

			/** @var \XF\Entity\Attachment $attachment */
			foreach ($attachments as $attachment)
			{
				if ($attachment->Data->width > 0)
				{
					if ($question->inline == 2)
					{
						$attachReply .= '[ATTACH=full]' . $attachment->attachment_id . '[/ATTACH]';
					}
					else
					{
						$attachReply .= '[ATTACH]' . $attachment->attachment_id . '[/ATTACH]';
					}

					$imageFound = true;
				}
			}

			if (!$imageFound)
			{
				$attachReply .= \XF::phrase('snog_forms_file_attached');
			}
		}
		else
		{
			$attachReply .= \XF::phrase('snog_forms_file_attached');
		}

		return $attachReply;
	}

	public function deleteQuestions($posid)
	{
		$this->db()->delete('xf_snog_forms_questions', 'posid = ?', $posid);
		$this->db()->delete('xf_snog_forms_answers', "posid = ?", $posid);
	}

	public function deleteAllQuestions()
	{
		$this->db()->emptyTable('xf_snog_forms_questions');
		$this->db()->emptyTable('xf_snog_forms_answers');
	}
}