<?php

namespace Snog\Forms\XF\Pub\Controller;

use Snog\Forms\Entity\Question;
use XF\Mvc\ParameterBag;

class Thread extends XFCP_Thread
{
	public function actionIndex(ParameterBag $params)
	{
		$view = parent::actionIndex($params);

		if ($view instanceof \XF\Mvc\Reply\View)
		{
			/** @var \Snog\Forms\XF\Entity\Thread $thread */
			$thread = $view->getParam('thread');

			if ($thread && $thread->Form && $thread->Form->quickreply)
			{
				$form = $thread->Form;

				/** @var \Snog\Forms\Repository\Question $questionRepo */
				$questionRepo = $this->repository('Snog\Forms:Question');
				$questions = $questionRepo->getQuestionList($form->posid);

				if (!$questions)
				{
					return $view;
				}

				$nodeTree = '';

				$expectedAnswers = [];
				$conditionQuestions = [];

				// SETUP CONDITIONALS AND ANSWERS FOR CHOICE QUESTIONS
				foreach ($questions as $question)
				{
					// BUILD AND SORT CONDITIONAL QUESTIONS
					if ($question->hasconditional)
					{
						/** @var \Snog\Forms\Repository\Question $questionRepo */
						$questionRepo = $this->repository('Snog\Forms:Question');
						$conditionals = $questionRepo->getQuestionConditionals($question, $questions);
						$conditionQuestions[$question['questionid']] = $conditionals;
					}

					if ($question->type == Question::TYPE_FORUM_SELECT)
					{
						/** @var \XF\Repository\Node $nodeRepo */
						$nodeRepo = $this->repository('XF:Node');
						$nodeTree = $nodeRepo->createNodeTree($nodeRepo->getFullNodeList());
					}
				}

				$warnings = [];
				$visitor = \XF::visitor();
				$canSubmit = $form->checkUserCriteriaMatch($visitor);
				if (!$canSubmit)
				{
					$warnings[] = \XF::phrase('snog_forms_not_allowed_to_submit');
				}

				if ($form->cooldown !== 0)
				{
					/** @var \Snog\Forms\ControllerPlugin\Form $formPlugin */
					$formPlugin = $this->plugin('Snog\Forms:Form');
					$cooldownError = $formPlugin->assertFormCooldown($form, $visitor, $this->request->getIp(), false);
					if (!empty($cooldownError))
					{
						$canSubmit = false;
						$warnings[] = $cooldownError;
					}
				}

				$viewParams = [
					'formExpectedAnswers' => $expectedAnswers,
					'formQuestions' => $questions,
					'formConditionQuestions' => $conditionQuestions,
					'formWarnings' => $warnings,
					'canSubmitForm' => $canSubmit,
					'nodeTree' => $nodeTree,
				];

				$view->setParams($viewParams);
			}
		}

		return $view;
	}

	protected function getThreadViewExtraWith()
	{
		$extraWith = parent::getThreadViewExtraWith();
		$extraWith[] = 'Form';
		return $extraWith;
	}
}