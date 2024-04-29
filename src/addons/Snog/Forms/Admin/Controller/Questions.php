<?php

namespace Snog\Forms\Admin\Controller;

use Snog\Forms\Entity\Question;
use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

class Questions extends AbstractController
{
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('snogFormsAdmin');
	}

	public function actionIndex()
	{
		$questionRepo = $this->getQuestionRepo();

		$questionTree = $questionRepo->createQuestionTree($questionRepo->getQuestionList());
		$conditionalPermitted = false;

		foreach ($questionTree as $tree)
		{
			/** @var Question $question */
			$question = $tree->record;

			if ($question->canTriggerConditionals())
			{
				$conditionalPermitted = true;
			}
		}

		$viewParams = [
			'questions' => $questionTree,
			'conditionalPermitted' => $conditionalPermitted
		];

		return $this->view('Snog:Forms\Questions', 'snog_forms_question_list', $viewParams);
	}

	public function actionFormQuestions(ParameterBag $params)
	{
		$questionRepo = $this->getQuestionRepo();

		$questionTree = $questionRepo->createQuestionTree($questionRepo->getQuestionList($params['posid']));
		$conditionalPermitted = false;

		foreach ($questionTree as $tree)
		{
			/** @var Question $question */
			$question = $tree->record;

			if ($question->canTriggerConditionals())
			{
				$conditionalPermitted = true;
			}
		}

		/** @var \Snog\Forms\Entity\Form $editForm */
		$editForm = $this->em()->findOne('Snog\Forms:Form', ['posid', '=', $params['posid']]);
		$viewParams = [
			'questions' => $questionTree,
			'form' => $editForm,
			'conditionalPermitted' => $conditionalPermitted
		];

		return $this->view('Snog:Forms\Questions', 'snog_forms_question_list', $viewParams);
	}

	public function actionSort(ParameterBag $params)
	{
		$posid = isset($params['posid']) ? $params['posid'] : 0;
		$questionRepo = $this->getQuestionRepo();
		$questionList = $questionRepo->createQuestionTree($questionRepo->getQuestionList($posid));

		if ($this->isPost())
		{
			/** @var \XF\ControllerPlugin\Sort $sorter */
			$sorter = $this->plugin('XF:Sort');

			$options = [
				'orderColumn' => 'display',
				'jump' => 1,
				'preSaveCallback' => null
			];

			$sortTree = $sorter->buildSortTree($this->filter('questions', 'json-array', 'posid'));
			$sorter->sortTree($sortTree, $questionList->getAllData(), 'display_parent', $options);

			if ($posid)
			{
				return $this->redirect($this->buildLink('form-editquestions/formquestions', $params));
			}

			return $this->redirect($this->buildLink('form-questions'));
		}

		$viewParams = [
			'questionList' => $questionList,
			'posid' => $posid,
			'form' => $params
		];

		return $this->view('Snog:Forms\Sort', 'snog_forms_question_order', $viewParams);
	}

	public function actionAdd(ParameterBag $params)
	{
		/** @var \Snog\Forms\Entity\Form $editForm */
		$editForm = $this->em()->findOne('Snog\Forms:Form', ['posid', '=', $params['posid']]);
		$questionRepo = $this->getQuestionRepo();

		/** @var Question[] $questions */
		$questions = $questionRepo->createQuestionTree($questionRepo->getQuestionList($params['posid']));

		/** @var Question[] $checkQuestions */
		$checkQuestions = $questionRepo->getQuestionList($params['posid']);
		$nextQuestion = count($questions) + 1;
		$fileType = false;
		$prefixType = false;

		foreach ($checkQuestions as $existing)
		{
			// CHECK IF THERE IS ALREADY A FILE UPLOAD QUESTION FOR THIS FORM
			if ($existing->type == Question::TYPE_FILE_UPLOAD)
			{
				$fileType = true;
			}

			// CHECK IF THERE IS ALREADY A PREFIX QUESTION FOR THIS FORM
			if ($existing->type == Question::TYPE_THREAD_PREFIX)
			{
				$prefixType = true;
			}
		}

		/** @var Question $question */
		$question = $this->em()->create('Snog\Forms:Question');
		return $this->questionAddEdit($question, $editForm, $nextQuestion, $fileType, $prefixType);
	}

	public function actionAddcon(ParameterBag $params)
	{
		if (!$params['posid']) $params['posid'] = 0;

		/** @var \Snog\Forms\Entity\Form $editForm */
		$editForm = $this->em()->findOne('Snog\Forms:Form', ['posid', '=', $params['posid']]);

		$questionRepo = $this->getQuestionRepo();

		/** @var Question[] $questions */
		$questions = $questionRepo->createQuestionTree($questionRepo->getQuestionList($params['posid']));

		/** @var Question[] $checkquestions */
		$checkquestions = $questionRepo->getQuestionList($params['posid']);
		$nextquestion = count($questions) + 1;
		$fileType = false;
		$prefixType = false;
		$conditionals = [];

		foreach ($checkquestions as $existing)
		{
			// CHECK IF THERE IS ALREADY A FILE UPLOAD QUESTION FOR THIS FORM
			if ($existing->type == Question::TYPE_FILE_UPLOAD)
			{
				$fileType = true;
			}

			// CHECK IF THERE IS ALREADY A PREFIX QUESTION FOR THIS FORM
			if ($existing->type == Question::TYPE_THREAD_PREFIX)
			{
				$prefixType = true;
			}

			// GET QUESTIONS THAT CAN TRIGGER CONDITIONALS
			if ($existing->canTriggerConditionals())
			{
				if ($existing->type == Question::TYPE_YES_NO)
				{
					$answers = [
						'1' => \XF::phrase('yes'),
						'2' => \XF::phrase('no')
					];
				}
				else
				{
					$answers = preg_split('/\r?\n/', $existing->expected);
				}

				$conditionals[] = [
					'questionId' => $existing->questionid,
					'text' => $existing->text,
					'type' => $existing->type,
					'answers' => $answers
				];
			}
		}

		if (empty($conditionals))
		{
			return $this->error(\XF::phrase('snog_forms_error_none_defined'));
		}

		/** @var Question $question */
		$question = $this->em()->create('Snog\Forms:Question');
		return $this->conquestionAddEdit($question, $editForm, $nextquestion, $fileType, $prefixType, $conditionals);
	}

	public function questionAddEdit($question, $editForm, $nextquesion = 0, $filetype = false, $prefixtype = false)
	{
		$viewParams = [
			'question' => $question,
			'form' => $editForm,
			'nextquestion' => $nextquesion,
			'filetype' => $filetype,
			'prefixtype' => $prefixtype
		];

		return $this->view('Snog:Forms\Question', 'snog_forms_question_edit', $viewParams);
	}

	public function conquestionAddEdit($question, $editForm, $nextquesion = 0, $filetype = false, $prefixtype = false, $conditionals = null)
	{
		$viewParams = [
			'question' => $question,
			'form' => $editForm,
			'nextquestion' => $nextquesion,
			'filetype' => $filetype,
			'prefixtype' => $prefixtype,
			'conditionals' => $conditionals
		];

		return $this->view('Snog:Forms\Question', 'snog_forms_con_question_edit', $viewParams);
	}

	public function actionEdit(ParameterBag $params)
	{
		$question = $this->assertQuestionExists($params['questionid']);
		$questionRepo = $this->getQuestionRepo();

		/** @var Question[] $questions */
		$questions = $questionRepo->getQuestionList($question['posid']);
		$fileType = false;
		$prefixType = false;

		foreach ($questions as $existing)
		{
			// CHECK IF THERE IS ALREADY A FILE UPLOAD QUESTION FOR THIS FORM
			if ($existing->type == Question::TYPE_FILE_UPLOAD && $existing->questionid !== $question->questionid)
			{
				$fileType = true;
			}

			// CHECK IF THERE IS ALREADY A PREFIX QUESTION FOR THIS FORM
			if ($existing->type == Question::TYPE_THREAD_PREFIX && $existing->questionid !== $question->questionid)
			{
				$prefixType = true;
			}
		}

		/** @var \Snog\Forms\Entity\Form $editForm */
		$editForm = $this->em()->findOne('Snog\Forms:Form', ['posid', '=', $question['posid']]);
		return $this->questionAddEdit($question, $editForm, 0, $fileType, $prefixType);
	}

	public function actionConEdit(ParameterBag $params)
	{
		$question = $this->assertQuestionExists($params['questionid']);
		$questionRepo = $this->getQuestionRepo();

		/** @var Question[] $questions */
		$questions = $questionRepo->getQuestionList($question['posid']);
		$fileType = false;
		$prefixType = false;
		$conditionals = [];

		foreach ($questions as $existing)
		{
			// CHECK IF THERE IS ALREADY A FILE UPLOAD QUESTION FOR THIS FORM
			if ($existing->type == Question::TYPE_FILE_UPLOAD && $existing->questionid !== $question->questionid)
			{
				$fileType = true;
			}

			// CHECK IF THERE IS ALREADY A PREFIX QUESTION FOR THIS FORM
			if ($existing->type == Question::TYPE_THREAD_PREFIX && $existing->questionid !== $question->questionid)
			{
				$prefixType = true;
			}

			// QUESTIONS THAT CAN BE USED AS CONDITIONAL TRIGGERS
			if ($existing->canTriggerConditionals())
			{
				if ($existing->type == Question::TYPE_YES_NO)
				{
					$answers = [
						'1' => \XF::phrase('yes'),
						'2' => \XF::phrase('no')
					];
				}
				else
				{
					$answers = preg_split('/\r?\n/', $existing->expected);
				}

				$conditionals[] = [
					'questionId' => $existing->questionid,
					'text' => $existing->text,
					'type' => $existing->type,
					'answers' => $answers
				];
			}
		}

		$editForm = $this->em()->findOne('Snog\Forms:Form', ['posid', '=', $question['posid']]);
		return $this->conquestionAddEdit($question, $editForm, 0, $fileType, $prefixType, $conditionals);
	}

	public function actionSave(ParameterBag $params)
	{
		if ($params->questionid)
		{
			/** @var Question $modifiedQuestion */
			$modifiedQuestion = $this->assertQuestionExists($params->questionid);
		}
		else
		{
			/** @var Question $modifiedQuestion */
			$modifiedQuestion = $this->em()->create('Snog\Forms:Question');
		}

		$this->questionSaveProcess($modifiedQuestion)->run();

		// ADD CONDITIONAL TO MASTER QUESTION CONDITIONAL LIST
		if ($modifiedQuestion['conditional'])
		{
			/** @var Question $masterQuestion */
			$masterQuestion = $this->em()->findOne('Snog\Forms:Question', ['questionid', '=', $modifiedQuestion['conditional']]);
			$existingConditionals = $masterQuestion->hasconditional;
			if (!$existingConditionals || !in_array($modifiedQuestion['questionid'], $existingConditionals)) $existingConditionals[] = $modifiedQuestion->questionid;
			$masterQuestion->hasconditional = $existingConditionals;
			$masterQuestion->save();
		}

		/** @var \Snog\Forms\Entity\Form $editForm */
		$editForm = $this->finder('Snog\Forms:Form')->where('posid', $modifiedQuestion['posid'])->fetchOne();

		if ($editForm)
		{
			return $this->redirect($this->buildLink('form-editquestions/formquestions', $editForm));
		}
		else
		{
			return $this->redirect($this->buildLink('form-questions'));
		}
	}

	public function actionAdddefault(ParameterBag $params)
	{
		$posid = $params['posid'];

		/** @var Question[] $defaultQuestions */
		$defaultQuestions = $this->finder('Snog\Forms:Question')->where('posid', '=', 0)->fetch();

		$conditionedQuestions = [];

		foreach ($defaultQuestions as $question)
		{
			/** @var Question $modifiedQuestion */
			$modifiedQuestion = $this->em()->create('Snog\Forms:Question');
			$newquestion['posid'] = $posid;
			$newquestion['text'] = $question->text;
			$newquestion['description'] = $question->description;
			$newquestion['type'] = $question->type;
			$newquestion['error'] = $question->error;
			$newquestion['expected'] = $question->expected;
			$newquestion['display'] = $question->display;
			$newquestion['display_parent'] = $question->display_parent;
			$newquestion['regex'] = $question->regex;
			$newquestion['regexerror'] = $question->regexerror;
			$newquestion['defanswer'] = $question->defanswer;
			$newquestion['showquestion'] = $question->showquestion;
			$newquestion['showunanswered'] = $question->showunanswered;
			$newquestion['questionpos'] = $question->questionpos;
			$newquestion['inline'] = $question->inline;
			$newquestion['format'] = $question->format;
			$newquestion['hasconditional'] = ($question->hasconditional ? $question->hasconditional : []);
			$newquestion['placeholder'] = $question->placeholder;
			$newquestion['checklimit'] = $question->checklimit;
			$newquestion['checkerror'] = $question->checkerror;

			// CHANGE PARENT FROM DEFAULT QUESTION TO NEW QUESTION
			if ($question->conditional)
			{
				foreach ($conditionedQuestions as $condition)
				{
					if ($condition['oldParent'] == $question->conditional)
					{
						$newquestion['conditional'] = $condition['newParent'];
						break;
					}
				}
			}

			$newquestion['conanswer'] = $question->conanswer;
			$this->questionAddDefaultProcess($modifiedQuestion, $newquestion)->run();

			// ADD PARENT INFO TO ARRAY
			if ($question->hasconditional)
			{
				$conditionedQuestions[] = [
					'oldParent' => $question->questionid,
					'oldParentConditionals' => $question->hasconditional,
					'newParent' => $modifiedQuestion->questionid
				];
			}

			// ADD PROPER CONDITIONAL QUESTIONS TO PARENT
			if ($question->conditional)
			{
				foreach ($conditionedQuestions as $condition)
				{
					if ($condition['oldParent'] == $question->conditional)
					{
						/** @var Question $parentQuestion */
						$parentQuestion = $this->em()->findOne('Snog\Forms:Question', ['questionid', '=', $condition['newParent']]);

						/** @var Question $oldParentQuestion */
						$oldParentQuestion = $this->em()->findOne('Snog\Forms:Question', ['questionid', '=', $condition['oldParent']]);

						/** @var Question[] $childQuestions */
						$childQuestions = $this->finder('Snog\Forms:Question')->where('conditional', $parentQuestion->questionid)->fetch();
						$existingConditionals = [];
						if ($parentQuestion->hasconditional)
						{
							$existingConditionals = $parentQuestion->hasconditional;
						}

						// CREATE NEW CONDITIONALS LIST FOR NEW PARENT
						if (empty($existingConditionals) || $childQuestions)
						{
							foreach ($childQuestions as $childQuestion)
							{
								if (!in_array($childQuestion->questionid, $existingConditionals))
								{
									$existingConditionals[] = $childQuestion->questionid;
								}
							}
						}

						// REMOVE DEFAULT CONDITIONAL QUESTIONS FROM EXISTING CONDITIONAL LIST
						if ($oldParentQuestion->posid == 0)
						{
							foreach ($condition['oldParentConditionals'] as $oldConditional)
							{
								if (($key = array_search($oldConditional, $existingConditionals)) !== false)
								{
									unset($existingConditionals[$key]);
								}
							}
						}

						$parentQuestion->hasconditional = $existingConditionals;
						$parentQuestion->save();
					}
				}
			}
		}

		$questionRepo = $this->getQuestionRepo();
		$questions = $questionRepo->createQuestionTree($questionRepo->getQuestionList($params['posid']));

		/** @var \Snog\Forms\Entity\Form $editForm */
		$editForm = $this->em()->findOne('Snog\Forms:Form', ['posid', $params['posid']]);

		$viewParams = [
			'questions' => $questions,
			'posid' => $editForm
		];

		return $this->view('Snog:Forms\Questions', 'snog_forms_question_list', $viewParams);
	}

	protected function questionAddDefaultProcess($modifiedQuestion, $values)
	{
		$questionAddDefault = $this->formAction();
		$questionAddDefault->basicEntitySave($modifiedQuestion, $values);
		return $questionAddDefault;
	}

	protected function questionSaveProcess(Question $modifiedQuestion)
	{
		$question = $this->formAction();
		$input = $this->filter([
			'text' => 'str',
			'description' => 'str',
			'type' => 'uint',
			'error' => 'str',
			'expected' => 'str',
			'display' => 'uint',
			'regex' => 'str',
			'regexerror' => 'str',
			'showquestion' => 'bool',
			'showunanswered' => 'bool',
			'questionpos' => 'uint',
			'posid' => 'uint',
			'inline' => 'uint',
			'format' => 'str',
			'conditional' => 'uint',
			'conanswer' => 'str',
			'checklimit' => 'uint',
			'checkerror' => 'str',
		]);

		// NO ERROR FOR QUESTION - CHECK IF QUESTION IS USED IN FORM TITLE
		if (!$input['error'] && $modifiedQuestion->posid)
		{
			/** @var \Snog\Forms\Entity\Form $form */
			$form = $this->em()->findOne('Snog\Forms:Form', ['posid', $modifiedQuestion->posid]);

			preg_match_all('/({A\d+})/', $form->subject, $titleAnswers);
			$questionIds = [];

			if (!empty($titleAnswers[1]))
			{
				foreach ($titleAnswers[1] as $titleAnswer)
				{
					$questionNumber = str_replace('{A', '', $titleAnswer);
					$questionNumber = str_replace('}', '', $questionNumber);
					$questionIds[] = $questionNumber;
				}
			}

			// IT'S IN THE FORM TITLE - THROW AN EXCEPTION
			if (in_array($modifiedQuestion->display, $questionIds))
			{
				throw $this->exception($this->notFound(\XF::phrase('snog_forms_error_question_used')));
			}
		}

		// ASSIGN PLACEHOLDER, EXPECTED ANSWERS, DEFAULT ANSWER AND REGEX VALUES
		$placeholders = $this->filter([
			'singleline' => 'str',
			'multiline' => 'str',
			'wysiwyg' => 'str',
		]);

		$expecteds = $this->filter([
			'expectedmulti' => 'str',
			'expectedmultiall' => 'str',
			'expectedagree' => 'str',
			'expectedsingle' => 'str',
			'expectedradio' => 'str',
			'expectedspin' => 'str',
		]);

		$defaults = $this->filter([
			'defsingleline' => 'str',
			'defmultiline' => 'str',
			'defyesno' => 'str',
			'defmulticheck' => 'str',
			'defmulticheckall' => 'str',
			'defsingledrop' => 'str',
			'defsingleforum' => 'str',
			'defradio' => 'str',
			'defspin' => 'str',
		]);

		$regs = $this->filter([
			'regexsingle' => 'str',
			'regexerrorsingle' => 'str',
			'regexmulti' => 'str',
			'regexerrormulti' => 'str',
			'regexspin' => 'str',
			'regexerrorspin' => 'str',
		]);

		switch ($input['type'])
		{
			case 1:
				$input['placeholder'] = $placeholders['singleline'];
				$input['defanswer'] = $defaults['defsingleline'];
				$input['regex'] = $regs['regexsingle'];
				$input['regexerror'] = $regs['regexerrorsingle'];
				break;

			case 2:
				$input['placeholder'] = $placeholders['multiline'];
				$input['defanswer'] = $defaults['defmultiline'];
				$input['regex'] = $regs['regexmulti'];
				$input['regexerror'] = $regs['regexerrormulti'];
				break;

			case 3:
				$input['defanswer'] = $defaults['defyesno'];
				break;

			case 4:
				$input['expected'] = $expecteds['expectedradio'];
				$input['defanswer'] = $defaults['defradio'];
				break;

			case 5:
				$input['expected'] = $expecteds['expectedmulti'];
				$input['defanswer'] = $defaults['defmulticheck'];
				break;

			case 7:
				$input['expected'] = $expecteds['expectedsingle'];
				$input['defanswer'] = $defaults['defsingledrop'];
				break;

			case 8:
				$input['expected'] = $expecteds['expectedmultiall'];
				$input['defanswer'] = $defaults['defmulticheckall'];
				break;

			case 9:
				$input['expected'] = $expecteds['expectedagree'];
				break;

			case 12:
				$input['defanswer'] = $defaults['defsingleforum'];
				break;

			case 13:
				$input['placeholder'] = $placeholders['wysiwyg'];
				break;

			case 14:
				$input['expected'] = $expecteds['expectedspin'];
				$input['defanswer'] = $defaults['defspin'];
				$input['regex'] = $regs['regexspin'];
				$input['regexerror'] = $regs['regexerrorspin'];
		}

		// QUESTION CHANGED AND CAN NO LONGER BE USED AS A TRIGGER FOR CONDITIONAL QUESTION
		if (!empty($modifiedQuestion->hasconditional) && !in_array($input['type'], [3, 4, 5, 7, 8]))
		{
			// DELETE CONDITIONAL QUESTIONS
			foreach ($modifiedQuestion->hasconditional as $conditional)
			{
				/** @var Question $oldConditional */
				$oldConditional = $this->em()->findOne('Snog\Forms:Question', ['questionid', '=', $conditional]);
				$oldConditional->delete();
			}

			$input['hasconditional'] = [];
		}

		$originalConditional = $this->filter('originalConditional', 'uint');
		$isConditional = $this->filter('isconditional', 'uint');

		// HEY DUMMY! YOU FORGOT TO SELECT A QUESTION TO TRIGGER THIS CONDITIONAL QUESTION
		if ($isConditional && !$input['conditional'])
		{
			$question->logError(\XF::phrase('snog_forms_error_conditional_question'));
		}

		// HEY DUMMY! YOU FORGOT TO SELECT AN ANSWER TO TRIGGER THIS CONDITIONAL QUESTION
		if ($isConditional && !$input['conanswer'])
		{
			$question->logError(\XF::phrase('snog_forms_error_conditional_answer'));
		}

		// CONDITIONAL CHANGED - REMOVE CONDITIONAL FROM ORIGINAL MASTER QUESTION CONDITIONAL LIST
		if ($originalConditional && $input['conditional'] !== $originalConditional)
		{
			/** @var Question $oldMasterQuestion */
			$oldMasterQuestion = $this->em()->findOne('Snog\Forms:Question', ['questionid', '=', $originalConditional]);
			if ($oldMasterQuestion)
			{
				$existingConditionals = $oldMasterQuestion->hasconditional;
				if (($key = array_search($modifiedQuestion->questionid, $existingConditionals)) !== false)
				{
					unset($existingConditionals[$key]);
				}
				$oldMasterQuestion->hasconditional = $existingConditionals;
				$oldMasterQuestion->save();
			}
		}

		// CLEAN EXPECTED ANSWERS FOR MULTIPLE CHOICE QUESTIONS OF EMPTY LINES IF PRESENT
		if (in_array($input['type'], [4, 5, 7, 8]))
		{
			$expectedArray = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $input['expected']);
			$input['expected'] = $expectedArray;
		}

		$question->basicEntitySave($modifiedQuestion, $input);
		return $question;
	}

	public function actionDelete(ParameterBag $params)
	{
		$question = $this->assertQuestionExists($params->questionid);

		if ($this->isPost())
		{
			$question->delete();

			$db = \XF::db();
			$db->delete("xf_snog_forms_answers", "questionid = ?", $params->questionid);

			/** @var \Snog\Forms\Entity\Form $editForm */
			$editForm = $this->em()->findOne('Snog\Forms:Form', ['posid', '=', $question['posid']]);

			if ($editForm)
			{
				return $this->redirect($this->buildLink('form-editquestions/formquestions', $editForm));
			}

			return $this->redirect($this->buildLink('form-questions'));
		}

		$viewParams = ['question' => $question];
		return $this->view('Snog:Forms\Question', 'snog_forms_confirm', $viewParams);
	}

	/**
	 * @param $id
	 * @param null $with
	 * @return Question|\XF\Mvc\Entity\Entity
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertQuestionExists($id, $with = null)
	{
		return $this->assertRecordExists('Snog\Forms:Question', $id, $with, 'snog_forms_question_not_found');
	}

	/**
	 * @return \Snog\Forms\Repository\Question|\XF\Mvc\Entity\Repository
	 */
	protected function getQuestionRepo()
	{
		return $this->repository('Snog\Forms:Question');
	}
}