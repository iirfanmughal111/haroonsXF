<?php

namespace Snog\Forms\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int questionid
 * @property int posid
 * @property string text
 * @property string description
 * @property int type
 * @property string error
 * @property string expected
 * @property int display
 * @property int display_parent
 * @property string regex
 * @property string regexerror
 * @property string defanswer
 * @property int $questionpos
 * @property int $showquestion
 * @property int $showunanswered
 * @property int $inline
 * @property string $format
 * @property array $hasconditional
 * @property int $conditional
 * @property string $conanswer
 * @property string $placeholder
 * @property int $checklimit
 * @property string $checkerror
 *
 * RELATIONS
 * @property Form $Form
 */
class Question extends Entity implements ExportableInterface
{
	/* ANSWER TYPES ($question->type)
	 1 - Single line text
	 2 - Multi-line text
	 3 - Yes/No radio buttons
	 4 - Single select radio buttons
	 5 - Multi-select checkboxes (array)
	 6 - Header phrase (not really an answer type)
	 7 - Single select dropdown
	 8 - Multi-select checkboxes w/select all option (array)
	 9 - Agreement with optional checkbox
	10 - File upload
	11 - Date
	12 - Single select forum list
	13 - WYSIWYG text
	14 - Numeric spinbox
	15 - Thread prefix
	*/
	const TYPE_TEXT = 1;
	const TYPE_MULTILINE_TEXT = 2;
	const TYPE_YES_NO = 3;
	const TYPE_RADIO = 4;
	const TYPE_CHECKBOXES = 5;
	const TYPE_HEADER_PHRASE = 6;
	const TYPE_SELECT = 7;
	const TYPE_MULTI_SELECT = 8;
	const TYPE_CHECKBOX = 9;
	const TYPE_FILE_UPLOAD = 10;
	const TYPE_DATE_INPUT = 11;
	const TYPE_FORUM_SELECT = 12;
	const TYPE_WYSIWYG = 13;
	const TYPE_SPINBOX = 14;
	const TYPE_THREAD_PREFIX = 15;

	public function getQuestionFormat($context = 'message')
	{
		$questionFormat = $this->format;

		if ($this->hasAnswerMessage())
		{
			// Question position in post if not on same line
			if (!$this->getOption('is_first'))
			{
				switch ($this->questionpos)
				{
					case 1:
						$questionFormat = "\r\n\r\n" . $this->format;
						break;
					case 2:
						$questionFormat = "\r\n" . $this->format;
				}
			}
		}

		return $questionFormat;
	}

	public function getFormattedReportMessage($answer, $context = 'message')
	{
		$questionFormat = $this->getQuestionFormat($context);

		// Not a header or thread prefix, add to message
		if ($this->hasAnswerMessage())
		{
			// Display question if selected
			$messageQuestion = $this->getShownQuestion($context);

			$messageAnswer = $this->getWrappedAnswerMessage(
				$this->getFormattedAnswer($answer, $context)
			);

			$questionResult = str_ireplace('{question}', $messageQuestion, $questionFormat);
			$questionResult = str_ireplace('{answer}', $messageAnswer, $questionResult);

			return $questionResult;
		}

		return '';
	}

	public function getDefaultAnswer(\XF\Entity\User $user = null)
	{
		$defAnswer = $this->defanswer;
		if ($defAnswer == '')
		{
			return $defAnswer;
		}

		if (!$user)
		{
			$user = \XF::visitor();
		}

		$defAnswer = str_replace('{username}', $user->username, $defAnswer);
		$defAnswer = str_replace('{userid}', $user->user_id, $defAnswer);
		$defAnswer = str_replace('{email}', $user->email, $defAnswer);

		if ($user->Profile)
		{
			$defAnswer = str_replace('{location}', $user->Profile->location, $defAnswer);
		}

		if ($customFields = $user->Profile->custom_fields)
		{
			if ($user->user_id)
			{
				foreach ($customFields as $key => $field)
				{
					if ($field)
					{
						if (is_array($field))
						{
							$defAnswer = str_replace('{custom.' . $key . '}', implode(',', $field), $defAnswer);
						}
						else
						{
							$defAnswer = str_replace('{custom.' . $key . '}', $field, $defAnswer);
						}
					}
					else
					{
						$defAnswer = str_replace('{custom.' . $key . '}', '', $defAnswer);
					}
				}
			}
			else
			{
				/** @var \XF\CustomField\DefinitionSet $definitionSet */
				$definitionSet = $this->app()->container('customFields.users');
				$fieldDefinitions = $definitionSet->getFieldDefinitions();

				foreach ($fieldDefinitions as $key => $field)
				{
					$defAnswer = str_replace('{custom.' . $key . '}', $defAnswer, '');
				}
			}
		}

		return $defAnswer;
	}

	public function isUploadNeeded()
	{
		return $this->type == self::TYPE_FILE_UPLOAD;
	}

	public function hasUrl()
	{
		return (bool) stristr($this->text, '[URL');
	}

	public function hasExpectedAnswer()
	{
		return $this->expected && in_array($this->type, [
				self::TYPE_RADIO, self::TYPE_CHECKBOXES, self::TYPE_SELECT, self::TYPE_MULTI_SELECT
			]);
	}

	/**
	 * @return array
	 */
	public function getExpectedAnswers()
	{
		if ($this->hasExpectedAnswer())
		{
			return preg_split('/\r?\n/', $this->expected) ?: [];
		}

		return [];
	}

	public function isAnswerStored()
	{
		return !in_array($this->type, [self::TYPE_HEADER_PHRASE, self::TYPE_FILE_UPLOAD]);
	}

	public function isUnanswered()
	{
		if (in_array($this->type, [self::TYPE_HEADER_PHRASE, self::TYPE_THREAD_PREFIX]))
		{
			return true;
		}
		elseif ($this->type == self::TYPE_CHECKBOX)
		{
			if (empty($this->expected))
			{
				return true;
			}
		}

		return false;
	}

	public function hasAnswerMessage()
	{
		return !in_array($this->type, [self::TYPE_HEADER_PHRASE, self::TYPE_THREAD_PREFIX]);
	}

	public function getFormattedAnswer($answer, $context = 'message')
	{
		if ($answer === null ||
			($this->type == self::TYPE_SELECT && $answer == 0) ||
			($this->type == self::TYPE_DATE_INPUT && $answer == '') ||
			($this->type == self::TYPE_FORUM_SELECT && $answer == 0))
		{
			// USER DID NOT ANSWER QUESTION
			if ($this->type == self::TYPE_CHECKBOX)
			{
				if (!empty($this->expected))
				{
					return \XF::phrase('snog_forms_no_answer');
				}
			}
			else
			{
				return \XF::phrase('snog_forms_no_answer');
			}
		}
		else
		{
			if ($this->type == self::TYPE_DATE_INPUT)
			{
				return $this->getDateAnswer($answer, $context);
			}
			elseif ($this->type == self::TYPE_CHECKBOX)
			{
				if (!empty($this->expected))
				{
					return $answer;
				}
			}
			elseif ($this->type == self::TYPE_YES_NO)
			{
				return $this->getYesNoAnswer($answer, $context);
			}
			elseif (in_array($this->type, [self::TYPE_RADIO, self::TYPE_SELECT]) && $this->expected)
			{
				return $this->getRadioSelectAnswer($answer, $context);
			}
			elseif ($this->type == self::TYPE_FORUM_SELECT)
			{
				return $this->getForumSelectAnswer($answer, $context);
			}
			elseif (is_array($answer))
			{
				return $this->getMultipleChoicesAnswer($answer, $context);
			}
			else
			{
				return $answer;
			}
		}

		return strval($answer);
	}

	public function canUsedForReportTitle()
	{
		return in_array($this->type, [
			self::TYPE_TEXT,
			self::TYPE_YES_NO,
			self::TYPE_RADIO,
			self::TYPE_SELECT,
			self::TYPE_DATE_INPUT,
			self::TYPE_FORUM_SELECT
		]);
	}

	public function getTitleAnswer($answer)
	{
		$titleAnswer = '';

		if ($this->canUsedForReportTitle())
		{
			if ($this->type == self::TYPE_DATE_INPUT)
			{
				return $this->getDateAnswer($answer, 'title');
			}
			elseif ($this->type == self::TYPE_YES_NO)
			{
				return $this->getYesNoAnswer($answer, 'title');
			}
			elseif ($this->expected && in_array($this->type, [self::TYPE_RADIO, self::TYPE_SELECT]))
			{
				return $this->getRadioSelectAnswer($answer, 'title');
			}
			elseif ($this->type == self::TYPE_FORUM_SELECT)
			{
				return $this->getForumSelectAnswer($answer, 'title');
			}
			elseif ($this->type == self::TYPE_TEXT)
			{
				return $answer;
			}
		}

		return $titleAnswer;
	}

	public function getMultipleChoicesAnswer($answers, $context = 'message')
	{
		if (is_array($answers))
		{
			$expectedArray = preg_split('/\r?\n/', $this->expected);
			$answerArray = [];

			foreach ($answers as $answer)
			{
				if ($answer !== 'all')
				{
					$answer = intval($answer);
					$answerArray[] = trim($expectedArray[$answer - 1]);
				}
			}

			return implode(', ', $answerArray);
		}

		return '';
	}

	public function getForumSelectAnswer($answer, $context = 'message')
	{
		/** @var \XF\Entity\Forum $forum */
		$forum = $this->em()->findCached('XF:Node', $answer);
		if (!$forum)
		{
			$forum = $this->em()->find('XF:Node', $answer);
		}

		if ($forum)
		{
			return $forum->title;
		}

		return '';
	}

	public function getRadioSelectAnswer($answer, $context = 'message')
	{
		$answerArray = preg_split('/\r?\n/', $this->expected);
		return isset($answerArray[$answer - 1]) ? trim($answerArray[$answer - 1]) : '';
	}

	public function getYesNoAnswer($answer, $context = 'message')
	{
		return $answer == 1 ? \XF::phrase('yes') : \XF::phrase('no');
	}

	public function getDateAnswer($answer, $context = 'message')
	{
		$convertDate = strtotime($answer);
		$newDate = date('Y-m-d', $convertDate);
		$newDate = $newDate . ' 12:00';
		$newStart = strtotime($newDate);

		return $this->app()->language()->date($newStart);
	}

	public function getShownQuestion($context = 'message')
	{
		$messageQuestion = '';

		if ($this->showquestion)
		{
			// AGREEMENT CHECK BOX
			if ($this->type == self::TYPE_CHECKBOX)
			{
				if ($this->expected > '')
				{
					$messageQuestion .= $this->expected;
				}
			}
			else
			{
				// REGULAR QUESTION
				$messageQuestion .= $this->text;
			}

			// SET QUESTION COLOR IF NEEDED
			$messageQuestion = $this->getWrappedQuestionMessage($messageQuestion);
		}

		return $messageQuestion;
	}

	public function getWrappedQuestionMessage($question)
	{
		if ($this->Form)
		{
			return $this->Form->getWrappedQuestionMessage($question);
		}

		return $question;
	}

	public function getWrappedAnswerMessage($answer)
	{
		if ($this->Form && $this->type !== self::TYPE_WYSIWYG)
		{
			return $this->Form->getWrappedAnswerMessage($answer);
		}

		return $answer;
	}

	public function canTriggerConditionals()
	{
		return in_array($this->type, [
			self::TYPE_YES_NO,
			self::TYPE_RADIO,
			self::TYPE_CHECKBOXES,
			self::TYPE_SELECT,
			self::TYPE_MULTI_SELECT
		]);
	}

	public function getAnswerErrors($answer)
	{
		$errors = [];

		// First special case where a default prefix can be set by admin
		// isset required in the event no prefixes can be used
		if (!empty($this->error))
		{
			if ($this->type == self::TYPE_THREAD_PREFIX && $this->Form && !$this->Form->prefix_ids && !$answer)
			{
				$errors[] = $this->error;
			}
			else
			{
				if ($this->type !== self::TYPE_THREAD_PREFIX)
				{
					$isTextType = in_array($this->type, [self::TYPE_TEXT, self::TYPE_MULTILINE_TEXT, self::TYPE_WYSIWYG]);

					if (!$isTextType && (!$answer))
					{
						$errors[] = $this->error;
					}

					if ($isTextType && (!$answer || $answer == ''))
					{
						$errors[] = $this->error;
					}
				}
			}
		}

		// CHECKBOX LIMIT ERRORS
		if ($answer && $this->hasCheckboxAnswerError())
		{
			$checkboxError = $this->getCheckboxAnswerError($answer, count($answer));
			if ($checkboxError)
			{
				$errors[] = $checkboxError;
			}
		}
		elseif ($answer && $this->hasRegexAnswerError())
		{
			$regexError = $this->getRegexAnswerError($answer);
			if ($regexError)
			{
				$errors[] = $regexError;
			}
		}

		return $errors;
	}

	public function hasCheckboxAnswerError()
	{
		return $this->type == self::TYPE_CHECKBOXES && $this->checklimit && !empty($this->checkerror);
	}

	public function getCheckboxAnswerError($answer, $checkedQuestionCount = 0)
	{
		if ($answer > '' && $checkedQuestionCount > $this->checklimit)
		{
			return $this->checkerror;
		}

		return '';
	}

	public function hasRegexAnswerError()
	{
		return ($this->type == self::TYPE_TEXT || $this->type == self::TYPE_MULTILINE_TEXT || $this->type == self::TYPE_SPINBOX)
			&& !empty($this->regex)
			&& !empty($this->regexerror);
	}

	public function getRegexAnswerError($answer)
	{
		if ($answer > '' && !preg_match('#' . str_replace('#', '\#', $this->regex) . '#siU', $answer))
		{
			return $this->regexerror;
		}

		return '';
	}

	public function getExportData(): array
	{
		return [
			'questionid' => $this->questionid,
			'posid' => $this->posid,
			'text' => htmlspecialchars($this->text),
			'description' => htmlspecialchars($this->description),
			'type' => $this->type,
			'error' => htmlspecialchars($this->error),
			'expected' => htmlspecialchars($this->expected),
			'display' => $this->display,
			'display_parent' => $this->display_parent,
			'regex' => htmlspecialchars($this->regex),
			'regexerror' => htmlspecialchars($this->regexerror),
			'defanswer' => htmlspecialchars($this->defanswer),
			'questionpos' => $this->questionpos,
			'showquestion' => $this->showquestion,
			'showunanswered' => $this->showunanswered,
			'format' => $this->format,
			'hasconditional' => serialize($this->hasconditional),
			'conditional' => $this->conditional,
			'conanswer' => $this->conanswer,
			'placeholder' => htmlspecialchars($this->defanswer),
			'checklimit' => $this->checklimit,
			'checkerror' => htmlspecialchars($this->checkerror)
		];
	}

	/************************* LIFE-CYCLE ***************************/

	protected function _preSave()
	{
		if (in_array($this->type, [self::TYPE_RADIO, self::TYPE_CHECKBOXES, self::TYPE_SELECT, self::TYPE_MULTI_SELECT]))
		{
			if (!$this->expected) $this->error(\XF::phrase('snog_forms_expected_error'));

			if ($this->defanswer)
			{
				$answers = preg_split('/\r?\n/', $this->expected);
				if (array_search(trim($this->defanswer), $answers) === false) $this->error(\XF::phrase('snog_forms_default_error'));
			}
		}

		if ($this->type == 3 && $this->defanswer)
		{
			$answer_1 = \XF::phrase('yes')->render();
			$answer_2 = \XF::phrase('no')->render();
			if ($this->defanswer !== $answer_1 && $this->defanswer !== $answer_2) $this->error(\XF::phrase('snog_forms_yesno_error'));
		}

		if ($this->type == 5 && $this->checklimit && !$this->checkerror)
		{
			$this->error(\XF::phrase('snog_forms_error_noerror'));
		}

		if ($this->type == 12 && $this->defanswer)
		{
			$node_name = $this->finder('XF:Node')->where('title', $this->defanswer)->fetchOne();
			if (!$node_name) $this->error(\XF::phrase('snog_forms_forum_error'));
		}

		if ($this->type !== 6 && stristr($this->format, '{question}') === false) $this->error(\XF::phrase('snog_forms_error_question'));
		if ($this->type !== 6 && stristr($this->format, '{answer}') === false) $this->error(\XF::phrase('snog_forms_error_answer'));
	}

	protected function _postDelete()
	{
		// Remove question from conditional list in master question
		if ($this->conditional)
		{
			/** @var Question $masterQuestion */
			$masterQuestion = $this->em()->find('Snog\Forms:Question', $this->conditional);
			if ($masterQuestion)
			{
				$existingConditionals = $masterQuestion->hasconditional;
				if (($key = array_search($this->questionid, $existingConditionals)) !== false) unset($existingConditionals[$key]);
				$masterQuestion->hasconditional = $existingConditionals;
				$masterQuestion->save();
			}
		}

		// Remove conditional questions when master question is deleted
		if ($this->hasconditional)
		{
			foreach ($this->hasconditional as $conditional)
			{
				/** @var \Snog\Forms\Entity\Question $conditionalQuestion */
				$conditionalQuestion = $this->em()->find('Snog\Forms:Question', $conditional);
				if ($conditionalQuestion)
				{
					$conditionalQuestion->delete();
				}
			}
		}

		// Renumbers question display order after delete
		if ($this->posid)
		{
			$posid = $this->posid;

			$questionRepo = $this->getQuestionRepo();
			$questionList = $questionRepo->getQuestionList($posid);
			$position = 1;

			foreach ($questionList as $question)
			{
				$this->db()->update('xf_snog_forms_questions', ['display' => $position], 'questionid = ?', $question['questionid']);
				$position++;
			}
		}
	}

	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_snog_forms_questions';
		$structure->shortName = 'Snog\Forms:Question';
		$structure->contentType = 'question';
		$structure->primaryKey = 'questionid';
		$structure->columns = [
			'questionid' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'posid' => ['type' => self::UINT, 'default' => 0],
			'text' => ['type' => self::STR, 'required' => 'snog_forms_question_text_error'],
			'description' => ['type' => self::STR, 'maxLength' => 250, 'default' => ''],
			'type' => ['type' => self::UINT, 'default' => 0],
			'error' => ['type' => self::STR, 'maxLength' => 200, 'default' => ''],
			'expected' => ['type' => self::STR],
			'display' => ['type' => self::UINT, 'default' => 0],
			'display_parent' => ['type' => self::UINT, 'default' => 0],
			'regex' => ['type' => self::STR, 'maxLength' => 255, 'default' => ''],
			'regexerror' => ['type' => self::STR, 'maxLength' => 200, 'default' => ''],
			'defanswer' => ['type' => self::STR, 'maxLength' => 200, 'default' => ''],
			'questionpos' => ['type' => self::UINT],
			'showquestion' => ['type' => self::UINT, 'default' => 1],
			'showunanswered' => ['type' => self::UINT, 'default' => 1],
			'inline' => ['type' => self::UINT, 'default' => 1],
			'format' => ['type' => self::STR, 'default' => ''],
			'hasconditional' => ['type' => self::JSON_ARRAY, 'default' => []],
			'conditional' => ['type' => self::UINT, 'default' => 0],
			'conanswer' => ['type' => self::STR, 'default' => ''],
			'placeholder' => ['type' => self::STR, 'maxLength' => 200, 'default' => ''],
			'checklimit' => ['type' => self::UINT, 'default' => 0],
			'checkerror' => ['type' => self::STR, 'maxLength' => 200, 'default' => ''],
		];

		$structure->getters = [];
		$structure->relations = [
			'Form' => [
				'entity' => 'Snog\Forms:Form',
				'type' => self::TO_ONE,
				'conditions' => 'posid',
				'primary' => true
			],
		];

		$structure->options['is_first'] = false;

		return $structure;
	}

	/**
	 * @return \Snog\Forms\Repository\Question|\XF\Mvc\Entity\Repository
	 */
	protected function getQuestionRepo()
	{
		return $this->repository('Snog\Forms:Question');
	}
}
