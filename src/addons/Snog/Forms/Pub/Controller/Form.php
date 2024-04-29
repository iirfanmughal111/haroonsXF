<?php

namespace Snog\Forms\Pub\Controller;

use Snog\Forms\Entity\Form as FormEntity;
use Snog\Forms\Entity\Question;
use XF;
use XF\Html\Renderer\BbCode;
use XF\Http\Request;
use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;

class Form extends AbstractController
{
	public function actionIndex()
	{
		$formRepo = $this->getFormRepo();

		/** @var \XF\Mvc\Entity\ArrayCollection|FormEntity[] $formValues */
		$formValues = $formRepo->findActiveFormsForList()->fetch()->filterViewable();

		$headerValues = [];

		// EXTRACT FORM TYPES FOR DISPLAY

		/** @var FormEntity $formHeader */
		foreach ($formValues as $formHeader) {
			if (isset($formHeader->Type->type) && !in_array($formHeader->Type->type, $headerValues)) {
				$headerValues[] = $formHeader->Type->type;
			}
			if (empty($formHeader->Type)) {
				$headerValues[] = null;
			}
		}

		$viewParams = [
			'forms' => $formValues,
			'headervalues' => $headerValues
		];

		return $this->view('Snog:Forms\Form', 'snog_forms_list', $viewParams);
	}

	public function actionSelect(ParameterBag $params)
	{
		$this->app->response()->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');

		$form = $this->assertViewableForm($params['posid'], ['Type', 'ConversationUser', 'Forum', 'SecondForum']);

		/** @var \Snog\Forms\XF\Entity\User $user */
		$user = XF::visitor();
		$replyThread = null;

		// GET THREAD ID FROM REPLY BUTTON IF NEEDED
		if ($form->qroption) {
			$replyThread = $this->filter('thread', 'uint');
		}

		/** @var \Snog\Forms\ControllerPlugin\Form $formPlugin */
		$formPlugin = $this->plugin('Snog\Forms:Form');
		$formPlugin->assertUsableFormFromContext($form, $user, $replyThread);

		// GET QUESTIONS
		$questionRepo = $this->getQuestionRepo();
		$questions = $questionRepo->getQuestionList($form->posid);
		$uploadNeeded = false;
		$forum = '';
		$prefixes = [];
		$conditionQuestions = [];

		// SETUP CONDITIONALS AND ANSWERS FOR CHOICE QUESTIONS
		foreach ($questions as $question) {
			// BUILD AND SORT CONDITIONAL QUESTIONS
			if ($question->hasconditional) {
				$conditionals = $questionRepo->getQuestionConditionals($question, $questions, $uploadNeeded);
				$conditionQuestions[$question['questionid']] = $conditionals;
			}

			// DETECT FILE UPLOAD QUESTION TYPE
			if ($question->isUploadNeeded()) {
				$uploadNeeded = true;
			}
		}

		$warnings = [];
		$canSubmit = $form->canSubmit();
		if (!$canSubmit) {
			$warnings[] = XF::phrase('snog_forms_not_allowed_to_submit');
		}

		if ($form->cooldown !== 0) {
			$cooldownError = $formPlugin->assertFormCooldown($form, $user, $this->request->getIp(), false);
			if (!empty($cooldownError)) {
				$canSubmit = false;
				$warnings[] = $cooldownError;
			}
		}

		// SETUP FOR FILE UPLOAD
		$noUpload = false;
		$attachmentData = null;

		if ($uploadNeeded && $canSubmit && (($form->inthread && $form->node_id) || $form->bypm || $form->oldthread || $replyThread)) {
			if ($form->inthread && $form->node_id) {
				$poster = $form->getPoster($user);
				$forum = $form->Forum;
				if ($forum) {
					$prefixes = $forum->getUsablePrefixes();

					if (!$forum->canCreateThreadFromForm($poster) || !$forum->canUploadAndManageAttachmentsFromForm($poster)) {
						$noUpload = true;
					}

					$attachmentRepo = $this->getAttachmentRepo();
					$attachmentData = $attachmentRepo->getEditorData('post', $forum, $forum->draft_thread['attachment_hash']);
				}
			} else {
				if ($form->oldthread || $replyThread) {
					$thread = $this->assertExistingThread($replyThread, $form);

					$attachmentHash = $thread->draft_reply->attachment_hash;
					$attachmentRepo = $this->getAttachmentRepo();
					$attachmentData = $attachmentRepo->getEditorData('post', $thread, $attachmentHash);
				} elseif ($form->bypm) {
					$draft = \XF\Draft::createFromKey('conversation');
					$attachmentRepo = $this->getAttachmentRepo();
					$attachmentData = $attachmentRepo->getEditorData('conversation_message', null, $draft->attachment_hash);
				}
			}
		}

		// GET PREFIXES IF FORUM NOT ALREADY DEFINED
		if (!$forum && $form->node_id) {
			$forum = $form->Forum;
			if ($forum) {
				$prefixes = $forum->getUsablePrefixes();
			}
		}

		// CHANGE THE STYLE FOR THIS FORM IF NEEDED
		if ($form->app_style) {
			$this->setViewOption('style_id', $form->app_style);
		}

		/** @var \XF\Repository\Node $nodeRepo */
		$nodeRepo = $this->repository('XF:Node');

		$viewParams = [
			'form' => $form,
			'forum' => $forum,
			'replythread' => $replyThread,
			'prefixes' => $prefixes,
			'noupload' => $noUpload,
			'questions' => $questions,
			'conditionQuestions' => $conditionQuestions,
			'nodeTree' => $nodeRepo->createNodeTree($nodeRepo->getFullNodeList()),
			'attachmentData' => $attachmentData,
			'canSubmit' => $canSubmit,
			'warnings' => $warnings
		];

		return $this->view('Snog:Forms\Form', 'snog_forms_form', $viewParams);
	}

	public function actionSubmit(ParameterBag $params)
	{
		$this->assertPostOnly();

		$form = $this->assertFormExists($params['posid'], [
			'Type',
			'PosterUser',
			'SecondaryPosterUser',
			'ConversationUser',
			'Forum',
			'SecondForum'
		]);

		$replyThread = null;

		// GET THREAD ID FROM COMPLETED FORM IF NEEDED
		if ($form->qroption) {
			$replyThreadId = $this->filter('replythread', 'uint');
			/** @var \XF\Entity\Thread $replyThread */
			$replyThread = $this->em()->findOne('XF:Thread', ['thread_id', '=', $replyThreadId]);
			if (!$replyThread) {
				return $this->noPermission();
			}
		}

		/** @var \Snog\Forms\ControllerPlugin\Form $formPlugin */
		$formPlugin = $this->plugin('Snog\Forms:Form');
		$formPlugin->assertUsableFormFromContext($form, $replyThread);

		if (!$form->canSubmit($error)) {
			return $this->noPermission($error);
		}

		$this->assertNotFlooding('form', $this->options()->ozzmodz_forms_floodCheck);

		/** @var \Snog\Forms\XF\Entity\User $user */
		$user = XF::visitor();

		if (!$this->captchaIsValid()) {
			return $this->error(XF::phrase('did_not_complete_the_captcha_verification_properly'));
		}

		$request = $this->app()->request();
		$ip = $request->getIp();

		if ($form->cooldown !== 0) {
			$formPlugin->assertFormCooldown($form, $user, $ip);
		}

		// GET QUESTIONS
		$questionRepo = $this->getQuestionRepo();
		$questions = $questionRepo->getQuestionList($form->posid);

		// GET & SETUP INITIAL VALUES
		$answers = $this->filter(['question' => 'array']);

		if (empty($answers['question'])) {
			return $this->error(XF::phrase('snog_forms_error_none_answered'));
		}

		$attachmentHash = $this->filter('attachment_hash', 'str');
		$attachedFile = false;
		$attachments = '';
		$errors = [];
		$firstQuestion = true;
		$forumNode = 0;
		$initialReportMessage = '';
		$unansweredCount = 0;
		$questionCount = 0;
		$thread = null;
		$returnPost = null;
		$postReturn = false;
		$conditionQuestions = [];
		$titleAnswers = [];

		$db = $this->app->db();
		$db->beginTransaction();

		/** @var \Snog\Forms\Repository\Log $logRepo */
		$logRepo = $this->repository('Snog\Forms:Log');
		$log = $logRepo->createLog($form, $user, $ip);

		// CHECK IF FILE(S) ARE UPLOADED
		if (!empty($attachmentHash)) {
			$attachRepo = $this->getAttachmentRepo();

			/** @var \XF\Mvc\Entity\ArrayCollection|\XF\Entity\Attachment[] $attachments */
			$attachments = $attachRepo->findAttachmentsByTempHash($attachmentHash)->fetch();
			$attachCount = $attachments->count();

			if ($form->minimum_attachments && ($attachCount < $form->minimum_attachments)) {
				return $this->error(XF::phrase('snog_forms_minimum_attachment_error', ['attachments' => $form->minimum_attachments]));
			}

			if ($attachCount > 0) {
				$attachedFile = true;
			}
		}

		/** @var \Snog\Forms\Repository\Question $questionRepo */
		$questionRepo = $this->repository('Snog\Forms:Question');

		// GET CONDITIONAL QUESTIONS
		foreach ($questions as $question) {
			if ($question->hasconditional) {
				$conditionals = $questionRepo->getQuestionConditionals($question, $questions);
				$conditionQuestions[$question->questionid] = $conditionals;
			}
		}

		// CHECK REQUIRED QUESTIONS
		foreach ($questions as $question) {
			$questionCount++;

			// PROCESS MAIN QUESTIONS
			if ($question->conditional) {
				// CHECK ANSWERED CONDITIONAL OF CONDITIONAL FOR REQUIRED ANSWERS
				if (isset($answers['question'][$question->questionid])) {
					$answers = $this->processConditionalQuestionsAnswers(
						$question,
						$questions,
						$conditionQuestions,
						$answers,
						$attachedFile,
						$attachments,
						$errors
					);
				}

				continue;
			}

			// HANDLE FILE UPLOADS FIRST
			if ($question->type == Question::TYPE_FILE_UPLOAD && $attachedFile) {
				$attachReply = $questionRepo->getFileUploadsAttachReply($question, $attachments);
				$answers['question'][$question->questionid] = $attachReply;
			}

			// PROCESS WYSIWYG EDITOR
			if ($question->type == Question::TYPE_WYSIWYG && !isset($answers['question'][$question->questionid])) {
				if (isset($answers['question'][$question->questionid . '_html'])) {
					$answers['question'][$question->questionid] = BbCode::renderFromHtml($answers['question'][$question->questionid . '_html']);
				}
			}

			$answer = $answers['question'][$question->questionid] ?? null;

			// Now check for required questions missing answers
			$errors += $question->getAnswerErrors($answer);

			// PROCESS REQUIRED CONDITIONAL QUESTIONS FOR THIS QUESTION
			$answers = $this->processConditionalQuestionsAnswers(
				$question,
				$questions,
				$conditionQuestions,
				$answers,
				$attachedFile,
				$attachments,
				$errors
			);
		}

		// DISPLAY ERRORS IF PRESENT
		if ($errors) {
			return $this->error($errors);
		}

		// POSSIBLE FUTURE USE
		//$message .= $form->aboveapp;

		// NO ERRORS - PROCESS THE FORM
		// INCLUDE NAME OR IP ADDRESS
		if ($form->incname) {
			if ($user->username) {
				$usernameQuestion = '[B]' . XF::phrase('user_name') . ':[/B] ';
				$initialReportMessage .= $form->getWrappedQuestionMessage($usernameQuestion);
				$initialReportMessage .= $form->getWrappedAnswerMessage($user->username);
			} else {
				$ipAddressQuestion = '[B]' . XF::phrase('ip_address') . ':[/B] ';
				$initialReportMessage .= $form->getWrappedQuestionMessage($ipAddressQuestion);
				$initialReportMessage .= $form->getWrappedAnswerMessage($ip);

				if ($form->bbstart && $form->bbend) {
					$initialReportMessage = $form->bbstart . $initialReportMessage . $form->bbend;
				}
			}

			$firstQuestion = false;
		}

		$threadPrefixes = 0;

		$storeAnswers = [];
		$reportMessages = [];

		/** @var Question $question */
		foreach ($questions as $question) {
			// Process main questions
			if ($question->conditional) {
				continue;
			}

			$answer = $answers['question'][$question->questionid] ?? null;
			if ((!$answer || empty($answer)) && !$question->showunanswered) {
				$unansweredCount++;
				continue;
			}

			if ($question->hasAnswerMessage() && $firstQuestion) {
				$firstQuestion = false;
			}

			$reportMessages = $this->processAnswer(
				$reportMessages,
				$form,
				$question,
				$answer,
				$firstQuestion,
				$unansweredCount,
				$titleAnswers,
				$storeAnswers
			);

			// SEPARATE ANSWER VALUES TO KEEP BB CODES OUT OF TITLE & DATABASE

			// PROCESS CONDITIONAL QUESTIONS FOR THIS QUESTION
			if (!empty($question->hasconditional) && $answer) {
				$reportMessages = $this->processConditionals(
					$reportMessages,
					$form,
					$question,
					$answers,
					$conditionQuestions,
					$questions,
					$unansweredCount,
					$titleAnswers,
					$storeAnswers
				);
			}
		}

		if ($storeAnswers) {
			/** @var \Snog\Forms\Repository\Answer $answerRepo */
			$answerRepo = $this->repository('Snog\Forms:Answer');
			$answerRepo->saveAnswers($storeAnswers, $form->posid, $log->log_id, $user->user_id);
		}

		// FINAL MESSAGE ERROR TRAPS
		if ($unansweredCount == $questionCount) {
			$errors[] = XF::phrase('snog_forms_error_none_answered');
			return $this->error($errors);
		}

		// POSSIBLE FUTURE USE
		//$message .= $form->belowapp;

		// BUILD REPORT TITLE
		$title = $form->subject;

		$formRepo = $this->getFormRepo();
		$title = $formRepo->getReportTitle($title, $titleAnswers, $user, $unansweredQuestionIds);

		// TITLE NOT COMPLETE - THROW ERROR
		if (!empty($unansweredQuestionIds)) {
			$errorCount = count($unansweredQuestionIds);
			$errorIDs = implode(',', $unansweredQuestionIds);

			if ($errorCount > 1) {
				$error = XF::phrase('snog_forms_error_title_plural', ['questions' => $errorIDs]);
			} else {
				$error = XF::phrase('snog_forms_error_title_single', ['question' => $errorIDs]);
			}

			return $this->error($error);
		}

		$title = substr($title, 0, 150);



		$postReportMessage = $initialReportMessage . implode('', $reportMessages['post'] ?? []);

		// FORM TO NEW THREAD
		if ($form->inthread && $forum = $form->Forum) {
			$watch = false;

			$poster = $form->getPoster($user);
			if ($poster === $user && $form->watchthread) {
				$watch = true;
			}

			$threadParams = [
				'title' => $title,
				'message' => $postReportMessage,
				'threadPrefixes' => $threadPrefixes,
				'watch' => $watch,
				'forum_node' => $forumNode,
				'attachment_hash' => $attachmentHash,
				'create_poll' => true
			];

			$thread = $this->createThread($poster, $forum, $form, $user, $threadParams);
		}

		// FORM TO SECOND THREAD
		if ($form->insecthread && $forum = $form->SecondForum) {
			$poster = $form->getSecondaryPoster($user);

			$threadParams = [
				'title' => $title,
				'message' => $postReportMessage,
				'threadPrefixes' => $threadPrefixes,
				'watch' => false,
				'forum_node' => $forumNode,
				'attachment_hash' => null,
				'create_poll' => false
			];

			$thread = $this->createThread($poster, $forum, $form, $user, $threadParams);
		}

		// FORM TO EXISTING THREAD
		if ($form->oldthread || $replyThread) {
			$thread = $this->assertExistingThread($replyThread, $form);
			$poster = $form->getPoster($user);

			$returnPost = $this->createReply($poster, $thread, $form, $postReportMessage, $attachmentHash);

			if ($user->user_id && $form->instant) {
				// SAVE PROMOTION INFO FOR APPROVE/DENY

				/** @var \Snog\Forms\Entity\Promotion $promotion */
				$promotion = $this->em()->create('Snog\Forms:Promotion');
				$promotion->post_id = $returnPost->post_id;
				$promotion->thread_id = $thread->thread_id;
				$promotion->user_id = $user->user_id;
				$promotion->posid = $form->posid;
				$promotion->approve = true;
				$promotion->original_group = $user->user_group_id;
				$promotion->original_additional = $user->secondary_group_ids;
				$promotion->new_group = $form->decidepromote;
				$promotion->new_additional = $form->pollpromote;
				$promotion->forum_node = $forumNode;

				$promotion->save(false, false);
			}

			if ($form->postapproval) {
				$returnPost->message_state = 'moderated';
				if ($returnPost->isChanged('message_state')) {
					$returnPost->save(false, false);
				}
			}

			$postReturn = true;
		}

		// SEND FORM BY PC
		$sender = $form->ConversationUser;
		if ($sender && $form->bypm) {
			$conversationReportMessage = $initialReportMessage . implode('', $reportMessages['conversation_message'] ?? []);
			$this->sendConversationMessage(
				$title,
				$conversationReportMessage,
				$sender,
				$form->pmto,
				[
					'conversationOptions' => [
						'conversation_open' => !$form->pmdelete,
					],
					'formatMessage' => $form->parseyesno,
					'attachmentHash' => $attachmentHash,
				]
			);
		}

		// SEND FORM BY EMAIL
		if ($form->email) {
			$toAddresses = explode(',', $form->email);
			$emailReportMessage = $initialReportMessage . implode('', $reportMessages['email'] ?? []);

			$mail = $this->setupMail($title, $emailReportMessage, $thread ?? null, [
				'form' => $form,
				'reportSender' => $user,
				'titleAnswers' => $titleAnswers
			]);

			foreach ($toAddresses as $toEmail) {
				$mail->setTo($toEmail);
				$mail->send();
			}
		}

		if ($user->user_id) {
			// SEND PC TO USER
			if ($sender && $form->pmapp && $sender->username !== $user->username) {
				$initialReportMessage = str_replace('{1}', $user->username, $form->pmtext);
				$initialReportMessage = str_replace('{2}', $this->options()->boardTitle, $initialReportMessage);
				$initialReportMessage = str_replace('{3}', $form->pmsender, $initialReportMessage);
				$this->sendConversationMessage(
					$title,
					$initialReportMessage,
					$sender,
					$user->username,
					[
						'conversationOptions' => [
							'conversation_open' => !$form->pmdelete,
						],
						'formatMessage' => $form->parseyesno,
					]
				);
			}

			// INSTANT PROMOTE
			if ($form->appadd) {
				/** @var \XF\Service\User\UserGroupChange $userGroupService */
				$userGroupService = $this->service('XF:User\UserGroupChange');
				$userGroupService->addUserGroupChange($user->user_id, 'formsInstantPromote' . $form->posid, $form->addto);
			}

			if ($form->apppromote) {
				/** @var \Snog\Forms\Repository\Promotion $promotionRepo */
				$promotionRepo = $this->repository('Snog\Forms:Promotion');
				$promotionRepo->applyPrimaryGroupChange($user->user_id, $form->promoteto);
			}

			// INCREMENT USER'S COUNT FOR THIS FORM
			if ($form->formlimit) {
				$user->adjustAdvancedFormsSubmitCount($form, 1);
				if ($user->isChanged('snog_forms')) {
					$user->save(false, false);
				}
			}
		}

		$form->fastUpdate('submit_count', $form->submit_count + 1);

		$db->commit();

		$redirectContext = [
			'thread' => $thread,
			'post' => $postReturn ? $returnPost : null,
		];

		// Safety jic something goes wrong with returnto
		return $this->redirect($form->getRedirectUrl($redirectContext), $form->thanks);
	}

	protected function setupMail($title, $message, $thread = null, $extraData = [])
	{
		$mail = $this->app->mailer()->newMail();
		$mail->setSender($this->options()->contactEmailAddress);
		$mail->setTemplate('snog_forms_email', [
			'subject' => $title,
			'message' => $message,
			'thread' => $thread ?? null
		]);

		return $mail;
	}

	protected function processConditionalQuestionsAnswers(
		Question $question,
		$questions,
		$conditionQuestions,
		$answers,
		$attachedFile,
		$attachments,
		array    &$errors
	) {
		if (!$question->hasconditional) {
			return $answers;
		}

		if (!isset($conditionQuestions[$question->questionid])) {
			return $answers;
		}

		foreach ($conditionQuestions[$question->questionid] as $condition) {
			$match = false;

			// isset REQUIRED IN THE EVENT CONDITIONAL TRIGGER QUESTION IS NOT REQUIRED
			$answer = $answers['question'][$question->questionid] ?? null;
			if (!$answer) {
				continue;
			}

			// ACCOUNT FOR CHECKBOX ARRAY
			if (is_array($answer) && in_array($condition['answer'], $answer)) {
				$match = true;
			} elseif ($condition['answer'] == $answer) {
				$match = true;
			}

			if (!$match) {
				continue;
			}

			$questionRepo = $this->getQuestionRepo();

			/** @var Question $conditionQuestion */
			foreach ($questions as $conditionQuestion) {
				if ($conditionQuestion->questionid != $condition['questionid']) {
					continue;
				}

				// HANDLE FILE UPLOADS FIRST
				if ($conditionQuestion->type == Question::TYPE_FILE_UPLOAD && $attachedFile) {
					$attachReply = $questionRepo->getFileUploadsAttachReply($conditionQuestion, $attachments);
					$answers['question'][$conditionQuestion->questionid] = $attachReply;
				}

				// PROCESS WYSIWYG EDITOR
				if ($conditionQuestion->type == Question::TYPE_WYSIWYG && !isset($answers['question'][$conditionQuestion->questionid])) {
					if (isset($answers['question'][$conditionQuestion->questionid . '_html'])) {
						$answers['question'][$conditionQuestion->questionid] = BbCode::renderFromHtml($answers['question'][$conditionQuestion->questionid . '_html']);
					}
				}

				$conditionalAnswer = $answers['question'][$conditionQuestion->questionid] ?? null;

				// Now check for required questions missing answers
				$errors += $conditionQuestion->getAnswerErrors($conditionalAnswer);
			}
		}

		return $answers;
	}

	public function actionApprove(ParameterBag $params)
	{
		/** @var \Snog\Forms\XF\Entity\User $visitor */
		$visitor = XF::visitor();
		if (!$visitor->canApproveAdvancedForms($error)) {
			return $this->noPermission($error);
		}

		$postID = $params['posid'];
		$approve = $this->assertPromotionExists($postID, ['Form', 'Form.ConversationUser', 'Thread']);

		if ($this->isPost()) {
			/** @var \XF\Entity\User $user */
			$user = $this->em()->find('XF:User', $approve->user_id);

			/** @var \Snog\Forms\Repository\Promotion $promotionRepo */
			$promotionRepo = $this->repository('Snog\Forms:Promotion');

			/** @var \Snog\Forms\Entity\Form $form */
			$form = $approve->Form;

			// REMOVE INSTANT PROMOTE
			if ($form->removeinstant) {
				/** @var \XF\Service\User\UserGroupChange $userGroupService */
				$userGroupService = $this->service('XF:User\UserGroupChange');
				$userGroupService->addUserGroupChange($user->user_id, 'formsInstantPromote' . $form->posid, []);

				$promotionRepo->applyPrimaryGroupChange($user->user_id, $approve->original_group);
			}

			// CLOSE THE POLL IF ONE EXISTS
			if ($approve->poll_id) {
				$promotionRepo->closePoll($approve);
			}

			// CHANGE PRIMARY GROUP
			if ($form->promote_type == 1) {
				$promotionRepo->applyPrimaryGroupChange($user->user_id, $form->decidepromote);
			}

			// HANDLE USER GROUP ADD IF NOT MAKING A MODERATOR
			if ($form->make_moderator <= 1 && $form->promote_type == 2) {
				/** @var \XF\Service\User\UserGroupChange $userGroupService */
				$userGroupService = $this->service('XF:User\UserGroupChange');
				$userGroupService->addUserGroupChange($user->user_id, 'formsAddGroups' . $form->posid, $approve->new_additional);
			}

			// XF HANDLES USER GROUP ADDS IF MAKING A MODERATOR - MAKE THE CALLS
			if ($form->make_moderator > 1) {
				/** @var \Snog\Forms\Repository\Promotion $promotionRepo */
				$promotionRepo = $this->repository('Snog\Forms:Promotion');
				$promotionRepo->promoteModerator($approve, $user);
			}

			// APPEND APPROVED TO POST

			/** @var \XF\Entity\Post $post */
			$post = $this->em()->find('XF:Post', $approve->post_id);
			$message = $post->message;
			$message .= "\r\n\r\n";
			$message .= "[SIZE=2]" . XF::phrase('snog_forms_approved_by') . " " . $visitor->username . "[/SIZE]";
			$post->message = $message;
			if ($post->isChanged('message')) {
				$post->save(false, false);
			}

			// Send approved pc
			if ($form->approved_title) {
				$sender = $form->ConversationUser;
				if ($sender) {
					$message = str_replace('{1}', $form->position, $form->approved_text);
					$this->sendConversationMessage(
						$form->approved_title,
						$message,
						$sender,
						$user->username,
						[
							'conversationOptions' => [
								'conversation_open' => !$form->pmdelete,
							],
							'formatMessage' => $form->parseyesno,
						]
					);
				}
			}

			// ALL DONE - DELETE THE PROMOTION RECORD
			$approve->delete();

			// INCLUDE EXTERNAL FILE
			if ($form->approved_file) {
				include $form->approved_file;
			}

			return $this->redirect($this->buildLink('threads', $approve->Thread));
		}

		/** @var \XF\Entity\Thread $thread */
		$thread = $this->em()->findOne('XF:Thread', ['first_post_id', '=', $postID]);
		$viewParams = ['approve' => $approve, 'title' => $thread->title];
		return $this->view('Snog:Forms\Form', 'snog_forms_confirm', $viewParams);
	}

	public function actionDeny(ParameterBag $params)
	{
		/** @var \Snog\Forms\XF\Entity\User $visitor */
		$visitor = XF::visitor();
		if (!$visitor->canApproveAdvancedForms($error)) {
			return $this->noPermission($error);
		}

		$postID = $params['posid'];
		$deny = $this->assertPromotionExists($postID, ['Form', 'Form.ConversationUser', 'Thread']);

		if ($this->isPost()) {
			/** @var \XF\Entity\User $user */
			$user = $this->em()->find('XF:User', $deny->user_id);

			/** @var \Snog\Forms\Entity\Form $form */
			$form = $deny->Form;

			// REMOVE INSTANT PROMOTE
			if ($form->removeinstant) {
				/** @var \XF\Service\User\UserGroupChange $userGroupSerivice */
				$userGroupSerivice = $this->service('XF:User\UserGroupChange');
				$userGroupSerivice->addUserGroupChange($user->user_id, 'formsInstantPromote' . $form->posid, []);

				/** @var \Snog\Forms\Repository\Promotion $promotionRepo */
				$promotionRepo = $this->repository('Snog\Forms:Promotion');
				$promotionRepo->applyPrimaryGroupChange($user->user_id, $deny->original_group);
			}

			// CLOSE POLL
			if ($deny->poll_id) {
				/** @var \Snog\Forms\Repository\Promotion $promotionRepo */
				$promotionRepo = $this->repository('Snog\Forms:Promotion');
				$promotionRepo->closePoll($deny);
			}

			// APPEND DENIED TO POST

			/** @var \XF\Entity\Post $post */
			$post = $this->em()->find('XF:Post', $deny->post_id);
			$message = $post->message;
			$message .= "\r\n\r\n";
			$message .= "[SIZE=2]" . XF::phrase('snog_forms_denied_by') . " " . $visitor->username . "[/SIZE]";
			$post->message = $message;
			if ($post->isChanged('message')) {
				$post->save(false, false);
			}

			// Send denied pc
			$sender = $form->ConversationUser;
			if ($sender && $form->denied_title) {
				$message = str_replace('{1}', $form->position, $form->denied_text);
				$this->sendConversationMessage(
					$form->denied_title,
					$message,
					$sender,
					$user->username,
					[
						'conversationOptions' => [
							'conversation_open' => !$form->pmdelete,
						],
						'formatMessage' => $form->parseyesno,
					]
				);
			}

			// ALL DONE - DELETE THE PROMOTION RECORD
			$deny->delete();

			return $this->redirect($this->buildLink('threads', $deny->Thread));
		}

		/** @var \XF\Entity\Thread $thread */
		$thread = $this->em()->findOne('XF:Thread', ['first_post_id', '=', $postID]);
		$viewParams = ['deny' => $deny, 'title' => $thread->title];
		return $this->view('Snog:Forms\Form', 'snog_forms_confirm', $viewParams);
	}

	public function actionExtend(ParameterBag $params)
	{
		/** @var \Snog\Forms\XF\Entity\User $visitor */
		$visitor = XF::visitor();
		if (!$visitor->canExtendAdvancedFormsPolls($error)) {
			return $this->noPermission($error);
		}

		$postID = $params['posid'];
		$extend = $this->assertPromotionExists($postID, ['Form', 'Thread']);

		/** @var \XF\Entity\Poll $poll */
		$poll = $this->em()->find('XF:Poll', $extend->poll_id);
		$close_date = $poll->close_date;
		$poll->close_date = $close_date + 86400;
		if ($poll->isChanged('close_date')) {
			$poll->save(false, false);
		}

		$extend->close_date = $close_date + 86400;
		if ($extend->isChanged('close_date')) {
			$extend->save(false, false);
		}

		return $this->redirect($this->buildLink('threads', $extend->Thread));
	}

	protected function assertViewableForm($id, $with = [])
	{
		$form = $this->assertFormExists($id, $with);
		if (!$form) {
			throw $this->exception($this->notFound());
		}
		if (!$form->canView($error)) {
			throw $this->exception($this->noPermission($error));
		}

		return $form;
	}

	/**
	 * @param $id
	 * @param null $with
	 * @return FormEntity|\XF\Mvc\Entity\Entity
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertFormExists($id, $with = null)
	{
		return $this->assertRecordExists('Snog\Forms:Form', $id, $with, 'snog_forms_form_not_found');
	}

	/**
	 * @param $id
	 * @param null $with
	 * @return \XF\Mvc\Entity\Entity|\Snog\Forms\Entity\Promotion
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertPromotionExists($id, $with = null)
	{
		return $this->assertRecordExists('Snog\Forms:Promotion', $id, $with, 'snog_forms_promotion_not_found');
	}

	/**
	 * @param $conversationId
	 * @param $sender
	 * @param array $extraWith
	 * @return \XF\Entity\ConversationUser|\XF\Mvc\Entity\Entity
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertViewableUserConversation($conversationId, $sender, array $extraWith = [])
	{
		/** @var \XF\Finder\ConversationUser $finder */
		$finder = $this->finder('XF:ConversationUser');

		$conversation = $finder->forUser($sender, false)
			->where('conversation_id', $conversationId)
			->with($extraWith)
			->fetchOne();

		if (!$conversation) {
			throw $this->exception($this->notFound(XF::phrase('requested_conversation_not_found')));
		}

		return $conversation;
	}


	protected function setupConversationCreate(\XF\Entity\User $sender, $options)
	{
		/** @var \XF\Service\Conversation\Creator $creator */
		$creator = $this->service('XF:Conversation\Creator', $sender);
		$creator->setOptions($options);
		$creator->setIsAutomated();

		return $creator;
	}

	protected function finalizeConversationCreate(\XF\Service\Conversation\Creator $creator)
	{
	}

	protected function sendConversationMessage($title, $message, \XF\Entity\User $sender, $receiver, array $options = [])
	{
		$options = array_replace([
			'conversationOptions' => [
				'open_invite' => false,
				'conversation_open' => true,
			],
			'attachmentHash' => null,
			'formatMessage' => true,
		], $options);

		$creator = $this->setupConversationCreate($sender, $options['conversationOptions']);

		$creator->setRecipients($receiver, false, false);
		$creator->setContent($title, $message, $options['formatMessage']);

		if ($options['attachmentHash']) {
			$creator->setAttachmentHash($options['attachmentHash']);
		}

		if (!$creator->validate($errors)) {
			throw $this->errorException($errors);
		}

		/** @var \XF\Entity\ConversationMaster $conversation */
		$conversation = $creator->save();
		$this->finalizeConversationCreate($creator);

		// DELETE PC FROM SENDER'S INBOX
		if (!$options['conversationOptions']['conversation_open']) {
			$userConv = $this->assertViewableUserConversation($conversation->conversation_id, $sender);
			$recipientState = 'deleted_ignored';
			$recipient = $userConv->Recipient;

			if ($recipient) {
				$recipient->recipient_state = $recipientState;
				$recipient->save();
			}
		}

		return true;
	}

	public static function getActivityDetails(array $activities)
	{
		$formData = [];
		$posIds = [];
		$router = XF::app()->router('public');

		foreach ($activities as $activity) {
			$posId = $activity->pluckParam('posid');
			if ($posId) $posIds[$posId] = $posId;
		}

		if ($posIds) {
			/** @var FormEntity[] $forms */
			$forms = XF::em()->findByIds('Snog\Forms:Form', $posIds);
			foreach ($forms as $id => $form) {
				$formData[$id] = ['position' => $form->position, 'url' => $router->buildLink('form')];
			}
		}

		$output = [];
		foreach ($activities as $key => $activity) {
			$posId = $activity->pluckParam('posid');
			$form = $posId && isset($formData[$posId]) ? $formData[$posId] : null;
			if ($form) {
				$output[$key] = ['description' => XF::phrase('snog_forms_filling_out'), 'title' => $form['position'], 'url' => $form['url'],];
			} else {
				$output[$key] = XF::phrase('snog_forms_viewing_list');
			}
		}

		return $output;
	}

	/**
	 * @param \XF\Entity\User $poster
	 * @param \XF\Entity\Forum $forum
	 * @param bool $format
	 * @param array $params
	 * @return \XF\Service\Thread\Creator
	 * @throws \Exception
	 */
	protected function setupThreadCreate(\XF\Entity\User $poster, \XF\Entity\Forum $forum, bool $format, $params = [])
	{
		if (!isset($params['title']) || !isset($params['message'])) {
			throw new \LogicException("Params not defined for thread creation");
		}

		return XF::asVisitor($poster, function () use ($forum, $format, $params) {
			/** @var \XF\Service\Thread\Creator $creator */
			$creator = XF::service('XF:Thread\Creator', $forum);
			$creator->setContent($params['title'], $params['message'], $format);
			$creator->setIsAutomated();

			return $creator;
		});
	}

	protected function finalizeThreadCreate(\XF\Service\Thread\Creator $creator, $params = [])
	{
		$creator->sendNotifications();

		$visitor = XF::visitor();
		if ($visitor->user_id) {
			$thread = $creator->getThread();

			/** @var \XF\Repository\Thread $threadRepo */
			$threadRepo = $this->repository('XF:Thread');
			$threadRepo->markThreadReadByVisitor($thread, $thread->post_date);

			if ($thread->discussion_state == 'moderated') {
				$this->session()->setHasContentPendingApproval();
			}
		}
	}

	/**
	 * @param \XF\Entity\User $poster
	 * @param \XF\Entity\Forum $forum
	 * @param FormEntity $form
	 * @param \XF\Entity\User $user
	 * @param array $params
	 * @return \XF\Entity\Thread
	 * @throws \XF\PrintableException
	 */
	protected function createThread(
		\XF\Entity\User  $poster,
		\XF\Entity\Forum $forum,
		FormEntity       $form,
		\XF\Entity\User  $user,
		$params = []
	) {
		if (empty($params)) {
			throw new \LogicException("Params not defined for thread creation");
		}

		$creator = $this->setupThreadCreate($poster, $forum, $form->parseyesno, $params);

		$attachmentHash = $params['attachment_hash'] ?? null;

		if ($attachmentHash) {
			$creator->setAttachmentHash($attachmentHash);
		}

		if ($form->postapproval) {
			$creator->setDiscussionState('moderated');
		} else {
			$creator->setDiscussionState('visible');
		}

		$pollCreatorSvc = null;
		$createPoll = $params['create_poll'] ?? null;

		if ($createPoll) {
			$pollCreatorSvc = null;
			/** @var \Snog\Forms\ControllerPlugin\Poll $pollPlugin */
			$pollPlugin = $this->plugin('Snog\Forms:Poll');

			if (($form->normalpoll || $form->postpoll)) {
				if (!$forum->isThreadTypeCreatable('poll')) {
					throw $this->errorException("Poll is not creatable in forum '{$forum->title}' for form '{$form->position}'. Please contact adminstrators.");
				}

				$creator->setDiscussionTypeAndData('poll', new Request($this->app()->inputFilterer(), []));
				$typeDataSaver = $creator->getTypeDataSaver();

				if ($typeDataSaver instanceof \XF\Service\Thread\TypeData\PollCreator) {
					$pollCreatorSvc = $typeDataSaver->getPollCreator();

					// CREATE PROMOTION POLL
					if (!$form->normalpoll && $form->postpoll && $form->pollquestion) {
						$pollPlugin->setupPollCreatorSvc($pollCreatorSvc, $form, 1);
					} elseif (!$form->postpoll && $form->normalpoll && $form->normalquestion) {
						// CREATE NORMAL POLL
						$pollPlugin->setupPollCreatorSvc($pollCreatorSvc, $form, 2);
					}
				}
			}
		}

		$threadPrefixes = $params['threadPrefixes'] ?? null;

		$addOns = $this->app()->container('addon.cache');
		$isMultiPrefix = isset($addOns['SV/MultiPrefix']);

		/** @var \SV\MultiPrefix\XF\Entity\Forum|\XF\Entity\Forum $forum */
		$filterUsablePrefixes = function ($prefixIds) use ($forum) {
			if (!is_array($prefixIds)) {
				return $forum->isPrefixUsable($prefixIds) ? $prefixIds : 0;
			}

			foreach ($prefixIds as $key => $prefixId) {
				if (!$forum->isPrefixUsable($prefixId)) {
					unset($prefixIds[$key]);
				}
			}
			return $prefixIds;
		};

		if ($isMultiPrefix) {
			$usablePrefixIds = $filterUsablePrefixes($form->prefix_ids);
		} else {
			$firstPrefix = array_values($form->prefix_ids)[0] ?? 0;
			$usablePrefixIds = $forum->isPrefixUsable($firstPrefix) ? $firstPrefix : [];
		}

		// SET ADMIN SET THREAD PREFIX
		if (!$threadPrefixes && $usablePrefixIds) {
			$creator->setPrefix($usablePrefixIds);
		} else {
			// USE PREFIX FROM FORM
			$threadPrefixes = $filterUsablePrefixes($threadPrefixes);
			if ($threadPrefixes) {
				$creator->setPrefix($threadPrefixes);
			} else {
				// Use default prefix for forum
				$defaultPrefix = $isMultiPrefix ? $forum->sv_default_prefix_ids : $forum->default_prefix_id;
				if ($defaultPrefix) {
					$creator->setPrefix($defaultPrefix);
				}
			}
		}

		if (!$creator->validate($errors)) {
			throw $this->errorException($errors);
		}

		/** @var \XF\Entity\Thread $thread */
		$thread = $creator->save();
		$this->finalizeThreadCreate($creator, $params);

		if ($createPoll) {
			$poll = [];

			/** @var \XF\Entity\Poll $poll */
			if (isset($pollCreatorSvc)) $poll = $pollCreatorSvc->getPoll();

			// SET UP FOR APPROVE/DENY LINKS AND SAVE PROMOTION INFO
			if ($user->user_id && ($form->postpoll || $form->instant)) {
				// SAVE PROMOTION INFO FOR APPROVE/DENY AND POLL RESULT

				/** @var \Snog\Forms\Entity\Promotion $promotion */
				$promotion = $this->em()->create('Snog\Forms:Promotion');
				$promotion->post_id = $thread->first_post_id;
				$promotion->thread_id = $thread->thread_id;
				if (isset($poll->poll_id) && $form->postpoll) {
					$promotion->poll_id = $poll->poll_id;
				}

				$promotion->user_id = $user->user_id;
				$promotion->posid = $form->posid;
				if (isset($poll->close_date) && $form->postpoll) {
					$promotion->close_date = $poll->close_date;
				}

				if ($form->instant) {
					$promotion->approve = true;
				}

				$promotion->original_group = $user->user_group_id;
				$promotion->original_additional = $user->secondary_group_ids;
				$promotion->new_group = $form->decidepromote;
				$promotion->new_additional = $form->pollpromote;
				$promotion->forum_node = $params['forum_node'];

				$promotion->save(false, false);
			}
		}

		if ($thread && isset($params['watch']) && $params['watch']) {
			$state = $user->Option->creation_watch_state == 'watch_email' ? 'watch_email' : 'watch_no_email';

			/** @var \XF\Repository\ThreadWatch $threadWatchRepo */
			$threadWatchRepo = $this->repository('XF:ThreadWatch');
			$threadWatchRepo->setWatchState($thread, $user, $state);
		}

		return $thread;
	}

	/**
	 * @param \XF\Entity\User $poster
	 * @param \XF\Entity\Thread $thread
	 * @param $message
	 * @param bool $format
	 * @return \XF\Service\Thread\Replier
	 * @throws \Exception
	 */
	protected function setupReplyCreate(\XF\Entity\User $poster, \XF\Entity\Thread $thread, $message, $format = true)
	{
		return XF::asVisitor($poster, function () use ($thread, $format, $message) {
			/** @var \XF\Service\Thread\Replier $replier */
			$replier = XF::service('XF:Thread\Replier', $thread);
			$replier->setIsAutomated();
			$replier->setMessage($message, $format);

			return $replier;
		});
	}

	protected function finalizeReplyCreate(\XF\Service\Thread\Replier $replier)
	{
		$replier->sendNotifications();
	}

	/**
	 * @param \XF\Entity\User $poster
	 * @param \XF\Entity\Thread $thread
	 * @param FormEntity $form
	 * @param string $message
	 * @param null $attachmentHash
	 * @return \XF\Entity\Post|\XF\Mvc\Entity\Entity
	 * @throws \Exception
	 */
	protected function createReply(
		\XF\Entity\User   $poster,
		\XF\Entity\Thread $thread,
		FormEntity        $form,
		$message = '',
		$attachmentHash = null
	) {
		$replier = $this->setupReplyCreate($poster, $thread, $message, $form->parseyesno);

		if ($attachmentHash) {
			$replier->setAttachmentHash($attachmentHash);
		}

		if (!$replier->validate($errors)) {
			throw $this->errorException($errors);
		}

		$post = $replier->save();
		$this->finalizeReplyCreate($replier);

		return $post;
	}

	protected function processConditionals(
		$reportMessages,
		FormEntity $form,
		Question $question,
		$answers,
		$conditionQuestions,
		$questions,
		&$unansweredCount,
		&$titleAnswers,
		&$storeAnswers = [],
		&$forumNode = null
	) {
		$answer = $answers['question'][$question->questionid] ?? null;
		if (!$question->hasconditional || !$answer || !isset($conditionQuestions[$question->questionid])) {
			return [];
		}

		foreach ($conditionQuestions[$question->questionid] as $condition) {
			/** @var Question $conditionQuestion */
			foreach ($questions as $conditionQuestion) {
				if ($conditionQuestion->questionid != $condition['questionid']) {
					continue;
				}

				// MATCH SINGLE SELECT ANSWER OR ANSWER IN CHECKBOX MULTIPLE ANSWER ARRAY
				if ($condition['answer'] == $answer || (is_array($answer) && in_array($condition['answer'], $answer))) {
					$conditionAnswer = $answers['question'][$conditionQuestion->questionid] ?? null;
					if (empty($conditionAnswer) && !$conditionQuestion->showunanswered) {
						$unansweredCount++;
						continue;
					}

					$reportMessages = $this->processAnswer(
						$reportMessages,
						$form,
						$conditionQuestion,
						$conditionAnswer,
						false,
						$unansweredCount,
						$titleAnswers,
						$storeAnswers
					);

					if ($conditionQuestion->hasconditional && $conditionAnswer !== null) {
						$reportTypeMessages = $this->processConditionals(
							$reportMessages,
							$form,
							$conditionQuestion,
							$answers,
							$conditionQuestions,
							$questions,
							$unansweredCount,
							$titleAnswers,
							$storeAnswers
						);
					}
				}
			}
		}

		return $reportMessages;
	}

	protected function processAnswer(
		$reportMessages,
		FormEntity $form,
		Question $question,
		$answer,
		$isFirstQuestion,
		&$unansweredCount,
		&$titleAnswers,
		&$storeAnswers
	) {
		if ($question->isUnanswered()) {
			$unansweredCount++;
		}

		$question->setOption('is_first', $isFirstQuestion);

		// STORE ANSWER TO DATABASE IF NOT A HEADER OR FILE UPLOAD
		if ($form->store && $question->isAnswerStored()) {
			$storeAnswers[] = [
				'questionid' => $question->questionid,
				'answer' => $question->getFormattedAnswer($answer, 'store'),
			];
		}

		$titleAnswers[$question->display] = $question->getTitleAnswer($answer);

		foreach ($form->getReportContentTypes() as $reportType) {
			$reportMessages[$reportType][] = $question->getFormattedReportMessage($answer, $reportType);
		}

		return $reportMessages;
	}

	protected function assertExistingThread($replyThread, FormEntity $form)
	{
		if ($replyThread) {
			/** @var \XF\Entity\Thread $thread */
			$thread = $this->em()->findOne('XF:Thread', ['thread_id', '=', $replyThread], ['Forum', 'Forum.Node']);
		} else {
			/** @var \XF\Entity\Thread $thread */
			$thread = $this->em()->findOne('XF:Thread', ['thread_id', '=', $form->oldthread], ['Forum', 'Forum.Node']);
		}

		if (!$thread) {
			throw $this->exception($this->notFound(XF::phrase('requested_thread_not_found')));
		}

		return $thread;
	}

	/**
	 * @return \XF\Repository\Attachment|\XF\Mvc\Entity\Repository
	 */
	protected function getAttachmentRepo()
	{
		return $this->repository('XF:Attachment');
	}

	/**
	 * @return \Snog\Forms\Repository\Question|\XF\Mvc\Entity\Repository
	 */
	protected function getQuestionRepo()
	{
		return $this->repository('Snog\Forms:Question');
	}

	/**
	 * @return \Snog\Forms\Repository\Form|\XF\Mvc\Entity\Repository
	 */
	protected function getFormRepo()
	{
		return $this->repository('Snog\Forms:Form');
	}
}
