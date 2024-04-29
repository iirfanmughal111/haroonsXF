<?php


namespace Snog\Forms\Widget;


use Snog\Forms\Entity\Question;
use XF;
use XF\Widget\AbstractWidget;

class Form extends AbstractWidget
{
	public function render()
	{
		$options = $this->options;

		/** @var \Snog\Forms\Entity\Form $form */
		$form = $this->em()->find('Snog\Forms:Form', $options['posid']);
		if (!$form)
		{
			return XF::phrase('snog_forms_form_not_found');
		}

		if (!$form->canView($error))
		{
			return $error;
		}

		$warnings = [];

		$canSubmit = $form->canSubmit($error);
		if (!$canSubmit)
		{
			$warnings[] = $error;
		}

		// GET QUESTIONS

		/** @var \Snog\Forms\Repository\Question $questionRepo */
		$questionRepo = $this->repository('Snog\Forms:Question');
		$questions = $questionRepo->getQuestionList($form->posid);
		$expectedAnswers = [];
		$uploadNeeded = false;
		$forum = '';
		$prefixes = [];
		$conditionQuestions = [];

		// SETUP CONDITIONALS AND ANSWERS FOR CHOICE QUESTIONS
		foreach ($questions as $question)
		{
			// BUILD AND SORT CONDITIONAL QUESTIONS
			if ($question->hasconditional)
			{
				$conditionals = $questionRepo->getQuestionConditionals($question, $questions, $uploadNeeded);
				$conditionQuestions[$question['questionid']] = $conditionals;
			}

			// DETECT FILE UPLOAD QUESTION TYPE
			if ($question->isUploadNeeded())
			{
				$uploadNeeded = true;
			}
		}

		$user = XF::visitor();

		if ($form->cooldown !== 0)
		{
			$controller = $this->app->controller('Snog\Forms:Form', $this->app->request());
			/** @var \Snog\Forms\ControllerPlugin\Form $formPlugin */
			$formPlugin = $controller->plugin('Snog\Forms:Form');

			$cooldownError = $formPlugin->assertFormCooldown($form, $user, $this->app->request->getIp(), false);
			if (!empty($cooldownError))
			{
				$canSubmit = false;
				$warnings[] = $cooldownError;
			}
		}

		// SETUP FOR FILE UPLOAD
		$noUpload = false;
		$attachmentData = null;

		if ($uploadNeeded && $canSubmit && (($form->inthread && $form->node_id) || $form->bypm || $form->oldthread))
		{
			if ($form->inthread && $form->node_id)
			{
				$poster = $form->getPoster($user);

				$forum = $form->Forum;
				if (!$forum)
				{
					return XF::phrase('requested_forum_not_found');
				}

				if (!$forum->canCreateThreadFromForm($poster) || $forum->canUploadAndManageAttachmentsFromForm($poster))
				{
					$noUpload = true;
				}

				/** @var \XF\Repository\Attachment $attachmentRepo */
				$attachmentRepo = $this->repository('XF:Attachment');
				$attachmentData = $attachmentRepo->getEditorData('post', $forum, $forum->draft_thread['attachment_hash']);
			}
			else
			{
				if ($form->oldthread)
				{
					/** @var \XF\Entity\Thread $thread */
					$thread = $this->em()->findOne('XF:Thread', ['thread_id', '=', $form->oldthread], ['Forum', 'Forum.Node']);

					if (!$thread)
					{
						return XF::phrase('requested_thread_not_found');
					}

					$attachmentHash = $thread->draft_reply->attachment_hash;

					/** @var \XF\Repository\Attachment $attachmentRepo */
					$attachmentRepo = $this->repository('XF:Attachment');
					$attachmentData = $attachmentRepo->getEditorData('post', $thread, $attachmentHash);
				}
				elseif ($form->bypm)
				{
					$draft = \XF\Draft::createFromKey('conversation');

					/** @var \XF\Repository\Attachment $attachmentRepo */
					$attachmentRepo = $this->repository('XF:Attachment');
					$attachmentData = $attachmentRepo->getEditorData('conversation_message', null, $draft->attachment_hash);
				}
			}
		}

		// GET PREFIXES IF FORUM NOT ALREADY DEFINED
		if (!$forum && $form->node_id)
		{
			$forum = $form->Forum;
		}

		/** @var \XF\Repository\Node $nodeRepo */
		$nodeRepo = $this->repository('XF:Node');

		$viewParams = [
			'form' => $form,
			'forum' => $forum,
			'replythread' => null,
			'prefixes' => $prefixes,
			'noupload' => $noUpload,
			'questions' => $questions,
			'conditionQuestions' => $conditionQuestions,
			'expected' => $expectedAnswers,
			'attachmentData' => $attachmentData,
			'nodeTree' => $nodeRepo->createNodeTree($nodeRepo->getFullNodeList()),
			'canSubmit' => $canSubmit,
			'warnings' => $warnings
		];

		return $this->renderer('snog_forms_widget_form', $viewParams);
	}

	public function verifyOptions(\XF\Http\Request $request, array &$options, &$error = null)
	{
		$options = $request->filter([
			'posid' => 'uint',
			'row' => 'bool'
		]);

		/** @var \Snog\Forms\Entity\Form $form */
		$form = $this->em()->find('Snog\Forms:Form', $options['posid']);
		if (!$form)
		{
			$error = XF::phrase('snog_forms_form_not_found');
			return false;
		}

		return true;
	}
}