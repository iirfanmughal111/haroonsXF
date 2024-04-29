<?php

namespace Snog\Forms\Cron;

/**
 * Cron entry for poll results.
 */
class Pollresults
{
	/**
	 * Processes poll results for promotions.
	 */
	public static function processResults()
	{
		$app = \XF::app();

		/** @var \Snog\Forms\Entity\Promotion[] $promotionValues */
		$promotionValues = $app->finder('Snog\Forms:Promotion')
			->with(['Form', 'Thread', 'closedPoll'])
			->where('poll_id', '>', 0)
			->where('close_date', '<=', time() - 5)
			->fetch();

		foreach ($promotionValues as $promotion)
		{
			// ONLY PROCESS PROMOTION POLLS
			if (isset($promotion->Form->postpoll) && $promotion->Form->postpoll)
			{
				if (isset($promotion->closedPoll->responses))
				{
					$results = $promotion->closedPoll->responses;
					$yesCount = 0;
					$noCount = 0;
					foreach ($results as $result)
					{
						if ($result['response'] == \XF::phrase('yes')) $yesCount = $result['response_vote_count'];
						if ($result['response'] == \XF::phrase('no')) $noCount = $result['response_vote_count'];
					}

					if ($yesCount > $noCount)
					{
						/** @var \Snog\Forms\Entity\Promotion $approve */
						$approve = $app->em()->find('Snog\Forms:Promotion', $promotion->post_id);

						/** @var \XF\Entity\User $user */
						$user = $app->em()->find('XF:User', $approve->user_id);

						// REMOVE INSTANT PROMOTE
						if ($promotion->Form->removeinstant)
						{
							/** @var \XF\Service\User\UserGroupChange $userGroupService */
							$userGroupService = \XF::service('XF:User\UserGroupChange');
							$userGroupService->addUserGroupChange($user->user_id, 'formsInstantPromote' . $promotion->Form->posid, []);

							/** @var \Snog\Forms\Repository\Promotion $promotionRepo */
							$promotionRepo = \XF::repository('Snog\Forms:Promotion');
							$promotionRepo->applyPrimaryGroupChange($user->user_id, $approve->original_group);
						}

						// CHANGE PRIMARY GROUP
						if ($promotion->Form->promote_type == 1)
						{
							/** @var \Snog\Forms\Repository\Promotion $promotionRepo */
							$promotionRepo = \XF::repository('Snog\Forms:Promotion');
							$promotionRepo->applyPrimaryGroupChange($user->user_id, $promotion->Form->decidepromote);
						}

						// HANDLE USER GROUP ADD IF NOT MAKING A MODERATOR
						if ($promotion->Form->make_moderator <= 1)
						{
							if ($promotion->Form->promote_type == 2)
							{
								/** @var \XF\Service\User\UserGroupChange $userGroupSerivice */
								$userGroupService = \XF::service('XF:User\UserGroupChange');
								$userGroupService->addUserGroupChange($user->user_id, 'formsAddGroups' . $promotion->Form->posid, $approve->new_additional);
							}
						}

						// XF HANDLES USER GROUP ADDS IF MAKING A MODERATOR - MAKE THE CALLS
						if ($promotion->Form->make_moderator > 1)
						{
							/** @var \Snog\Forms\Repository\Promotion $promotionRepo */
							$promotionRepo = \XF::repository('Snog\Forms:Promotion');
							$promotionRepo->promoteModerator($promotion, $user);
						}

						// APPEND APPROVED TO POST

						/** @var \XF\Entity\Post $post */
						$post = $app->em()->find('XF:Post', $approve->post_id);
						$message = $post->message;
						$message .= "\r\n\r\n";
						$message .= "[SIZE=2]" . \XF::phrase('snog_forms_approved_by') . " " . \XF::phrase('poll_results') . "[/SIZE]";
						$post->message = $message;
						if ($post->isChanged('message')) $post->save(false, false);

						// SEND APPROVED PC
						if ($promotion->Form->approved_title)
						{
							/** @var \XF\Entity\User $sender */
							$sender = $app->finder('XF:User')->where('username', $promotion->Form->pmsender)->fetchOne();
							$message = str_replace('{1}', $promotion->Form->position, $promotion->Form->approved_text);
							self::sendPC($promotion->Form->approved_title, $message, $sender, $user->username, $promotion->Form->pmdelete, $promotion->Form->parseyesno);
						}

						// ALL DONE - DELETE THE PROMOTION RECORD
						$approve->delete();

						// INCLUDE EXTERNAL FILE
						if ($promotion->Form->approved_file) include $promotion->Form->approved_file;
					}

					if ($noCount > $yesCount)
					{
						/** @var \Snog\Forms\Entity\Promotion $deny */
						$deny = $app->em()->find('Snog\Forms:Promotion', $promotion->post_id);

						/** @var \XF\Entity\User $user */
						$user = $app->em()->find('XF:User', $promotion->user_id);

						// REMOVE INSTANT PROMOTE
						if ($promotion->Form->removeinstant)
						{
							/** @var \XF\Service\User\UserGroupChange $userGroupSerivice */
							$userGroupService = \XF::service('XF:User\UserGroupChange');
							$userGroupService->addUserGroupChange($user->user_id, 'formsInstantPromote' . $promotion->Form->posid, []);

							/** @var \Snog\Forms\Repository\Promotion $promotionRepo */
							$promotionRepo = \XF::repository('Snog\Forms:Promotion');
							$promotionRepo->applyPrimaryGroupChange($user->user_id, $promotion->original_group);
						}

						// APPEND DENIED TO POST

						/** @var \XF\Entity\Post $post */
						$post = $app->em()->find('XF:Post', $promotion->post_id);
						$message = $post->message;
						$message .= "\r\n\r\n";
						$message .= "[SIZE=2]" . \XF::phrase('snog_forms_denied_by') . " " . \XF::phrase('poll_results') . "[/SIZE]";
						$post->message = $message;
						if ($post->isChanged('message')) $post->save(false, false);

						// SEND DENIED PC
						if ($promotion->Form->denied_title)
						{
							$sender = $app->finder('XF:User')->where('username', $promotion->Form->pmsender)->fetchOne();
							$message = str_replace('{1}', $promotion->Form->position, $promotion->Form->denied_text);
							self::sendPC($promotion->Form->denied_title, $message, $sender, $user->username, $promotion->Form->pmdelete, $promotion->Form->parseyesno);
						}

						// ALL DONE - DELETE THE PROMOTION RECORD
						$deny->delete();
					}

					if ($yesCount == $noCount)
					{
						/** @var \Snog\Forms\Entity\Promotion $tie */
						$tie = $app->em()->find('Snog\Forms:Promotion', $promotion->post_id);

						$router = $app->router('public');
						$link = $router->buildLink('full:threads', $promotion->Thread);
						$poll = $promotion->Thread->title;
						$title = \XF::phrase('snog_forms_tie_title');

						// SEND TIE PC

						/** @var \XF\Entity\User $sender */
						$sender = $app->finder('XF:User')->where('username', $promotion->Form->pmsender)->fetchOne();
						$message = \XF::phrase('snog_forms_x_result_tie', ['link' => $link, 'poll' => $poll]);
						self::sendPC($title, $message, $sender, $promotion->Form->pmerror, $promotion->Form->pmdelete, true);

						// ADD DECISION LINKS TO POST IF NOT ALREADY THERE
						$promotion->approve = true;
						if ($promotion->isChanged('approve')) $promotion->save(false, false);

						// REMOVE POLL INFO TO PREVENT SPAMMING OF PC IN BOX
						$tie->poll_id = 0;
						$tie->close_date = 0;
						if ($tie->isChanged('poll_id')) $tie->save(false, false);
					}
				}
				else
				{
					/** @var \Snog\Forms\Entity\Promotion $errorPost */
					$errorPost = $app->em()->find('Snog\Forms:Promotion', $promotion->post_id);

					$router = $app->router('public');
					$link = $router->buildLink('full:threads', $promotion->Thread);
					$poll = $promotion->Thread->title;
					$title = \XF::phrase('snog_forms_cron_error_title');

					// SEND ERROR PC

					/** @var \XF\Entity\User $sender */
					$sender = $app->finder('XF:User')->where('username', $promotion->Form->pmsender)->fetchOne();
					$message = \XF::phrase('snog_forms_cron_error', ['link' => $link, 'poll' => $poll]);
					self::sendPC($title, $message, $sender, $promotion->Form->pmerror, $promotion->Form->pmdelete, true);

					// ADD DECISION LINKS TO POST IF NOT ALREADY THERE
					$promotion->approve = true;
					if ($promotion->isChanged('approve')) $promotion->save(false, false);

					// REMOVE POLL INFO TO PREVENT SPAMMING OF PC IN BOX
					$errorPost->poll_id = 0;
					$errorPost->close_date = 0;
					if ($errorPost->isChanged('poll_id')) $errorPost->save(false, false);
				}
			}
		}
	}

	protected static function sendPC($title, $message, $sender, $receiver, $close = true, $parse = false)
	{
		$app = \XF::app();
		$options['open_invite'] = false;
		$options['conversation_open'] = ($close) ? false : true;

		/** @var \XF\Service\Conversation\Creator $creator */
		$creator = $app->service('XF:Conversation\Creator', $sender);
		$creator->setOptions($options);
		$creator->setRecipients($receiver, false, false);
		$creator->setContent($title, $message, ($parse) ? true : false);
		$creator->setIsAutomated();
		$creator->save();

		$conversation = $creator->getConversation();

		// DELETE PC FROM SENDER'S INBOX
		if ($close)
		{
			/** @var \XF\Finder\ConversationUser $finder */
			$finder = $app->finder('XF:ConversationUser');

			/** @var \XF\Entity\ConversationUser $userConv */
			$userConv =  $finder->forUser($sender, false)
				->where('conversation_id', $conversation->conversation_id)
				->fetchOne();

			if (!$userConv)
			{
				throw new \LogicException(\XF::phrase('requested_conversation_not_found'));
			}

			$recipientState = 'deleted_ignored';
			$recipient = $userConv->Recipient;

			if ($recipient)
			{
				$recipient->recipient_state = $recipientState;
				$recipient->save();
			}
		}

		return true;
	}
}