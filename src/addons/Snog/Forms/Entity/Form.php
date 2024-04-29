<?php

namespace Snog\Forms\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $posid
 * @property string $position
 * @property int $node_id
 * @property int $secnode_id
 * @property bool $active
 * @property string $subject
 * @property string $email
 * @property int $email_parent
 * @property bool $inthread
 * @property bool $insecthread
 * @property string $posterid
 * @property string $secposterid
 * @property bool $bypm
 * @property string $pmsender
 * @property bool $pmdelete
 * @property string $pmerror
 * @property string $pmto
 * @property int $appid
 * @property array $prefix_ids
 * @property int $returnto
 * @property string $returnlink
 * @property bool $postapproval
 * @property bool $parseyesno
 * @property bool $incname
 * @property int $oldthread
 * @property bool $pmapp
 * @property string $pmtext
 * @property bool $apppromote
 * @property int $promoteto
 * @property bool $appadd
 * @property array $addto
 * @property bool $postpoll
 * @property bool $pollpublic
 * @property bool $pollchange
 * @property bool $pollview
 * @property string $pollquestion
 * @property int $promote_type
 * @property int $pollclose
 * @property int $decidepromote
 * @property array $pollpromote
 * @property bool $removeinstant
 * @property string $approved_title
 * @property string $approved_text
 * @property string $denied_title
 * @property string $denied_text
 * @property int $app_style
 * @property array $user_criteria
 * @property bool $watchthread
 * @property int $make_moderator
 * @property int $forum
 * @property bool $instant
 * @property string $aboveapp
 * @property string $belowapp
 * @property string $approved_file
 * @property bool $normalpoll
 * @property bool $normalpublic
 * @property bool $normalchange
 * @property bool $normalview
 * @property int $normalclose
 * @property string $normalquestion
 * @property string $threadbutton
 * @property bool $threadapp
 * @property int $formlimit
 * @property array $response
 * @property string $thanks
 * @property string $qcolor
 * @property string $acolor
 * @property array $forummod
 * @property array $supermod
 * @property int $quickreply
 * @property bool $store
 * @property int $start
 * @property int $end
 * @property bool $qroption
 * @property string $qrbutton
 * @property bool $qrstarter
 * @property array $qrforums
 * @property string $aftererror
 * @property string $bbstart
 * @property string $bbend
 * @property int $display_parent
 * @property int $display
 * @property int $minimum_attachments
 * @property int $submit_count
 * @property bool $is_public_visible
 * @property int $cooldown
 *
 * RELATIONS
 * @property Type $Type
 * @property Question[] $Questions
 * @property \Snog\Forms\XF\Entity\Forum $Forum
 * @property \XF\Entity\Forum $SecondForum
 * @property \XF\Entity\User $PosterUser
 * @property \XF\Entity\User $SecondaryPosterUser
 * @property \XF\Entity\User $ConversationUser
 *
 * @property Form $Form
 */
class Form extends Entity implements ExportableInterface
{
	public function canView(&$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->hasPermission('snogForms', 'canViewForms'))
		{
			return false;
		}

		if (!$this->isFormDateActive())
		{
			return false;
		}

		if ($this->isLimitReached())
		{
			$error = \XF::phrase('snog_forms_count_exceeded');
			return false;
		}

		if (!$this->is_public_visible)
		{
			$isMatched = $this->checkUserCriteriaMatch($visitor);
			if (!$isMatched)
			{
				$error = \XF::phrase('snog_forms_you_not_meet_form_criteria');
				return false;
			}
		}

		return true;
	}

	public function canSubmit(&$error = null)
	{
		$visitor = \XF::visitor();

		// Quick reply forms do not check criteria
		if (!$this->quickreply)
		{
			if (!$this->isFormDateActive())
			{
				return false;
			}

			if ($this->isLimitReached())
			{
				$error = \XF::phrase('snog_forms_count_exceeded');
				return false;
			}

			$isMatched = $this->checkUserCriteriaMatch($visitor);
			if (!$isMatched)
			{
				$error = \XF::phrase('snog_forms_you_not_meet_form_criteria');
				return false;
			}
		}

		return true;
	}

	public function isLimitReached()
	{
		if (!$this->formlimit)
		{
			return false;
		}

		/** @var \Snog\Forms\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		$userSubmitCount = $visitor->getAdvancedFormsSubmitCount($this);
		if ($userSubmitCount >= $this->formlimit)
		{
			return true;
		}

		return false;
	}

	/**
	 * @param \XF\Entity\User $user
	 * @return \XF\Entity\User|Entity|null
	 */
	public function getPoster(\XF\Entity\User $user)
	{
		// Use the user defined in form
		if ($this->posterid && $this->PosterUser)
		{
			return $this->PosterUser;
		}
		else if ($user->user_id)
		{
			// Use the actual user filling out the form
			return $user;
		}

		// Fall back to first super admin

		/** @var \XF\Entity\Admin $admin */
		$admin = $this->finder('XF:Admin')
			->with('User')
			->where('is_super_admin', 1)
			->fetchOne();

		return $admin ? $admin->User : null;
	}

	/**
	 * @param \XF\Entity\User $user
	 * @return \XF\Entity\User|Entity|null
	 */
	public function getSecondaryPoster(\XF\Entity\User $user)
	{
		// Use the user defined in form
		if ($this->secposterid && $this->SecondaryPosterUser)
		{
			return $this->SecondaryPosterUser;
		}
		else if ($user->user_id)
		{
			// USE THE ACTUAL USER FILLING OUT THE FORM
			return $user;
		}

		// FALL BACK TO FIRST SUPER ADMIN

		/** @var \XF\Entity\Admin $admin */
		$admin = $this->finder('XF:Admin')
			->with('User')
			->where('is_super_admin', 1)
			->fetchOne();

		return $admin ? $admin->User : null;
	}

	public function isFormDateActive(&$error = null)
	{
		if ($this->start || $this->end)
		{
			$xfTime = \XF::$time;

			if ($this->start && $this->start > $xfTime)
			{
				return false;
			}

			if ($this->end && $this->end < $xfTime)
			{
				if ($this->aftererror)
				{
					$error = $this->aftererror;
					return false;
				}
				else
				{
					return false;
				}
			}
		}

		return true;
	}

	public function checkUserCriteriaMatch(\XF\Entity\User $user, $matchOnEmpty = false, $checkType = true)
	{
		$isMatched = true;
		if ($checkType && $this->Type && $this->Type->user_criteria)
		{
			$isMatched = $this->Type->checkUserCriteriaMatch($user);
		}

		if ($this->user_criteria)
		{
			$userCriteria = $this->app()->criteria('XF:User', $this->user_criteria);
			$userCriteria->setMatchOnEmpty($matchOnEmpty);

			return $isMatched && $userCriteria->isMatched($user);
		}

		return $isMatched;
	}

	protected function verifyUserCriteria(&$criteria)
	{
		$userCriteria = $this->app()->criteria('XF:User', $criteria);
		$criteria = $userCriteria->getCriteria();
		return true;
	}

	public function getRedirectUrl($contexts = [])
	{
		$router = $this->app()->router('public');

		// SAFETY JIC SOMETHING GOES WRONG WITH returnto
		$returnLink = $router->buildLink('index');

		if ($this->returnto == 1)
		{
			$returnLink = $router->buildLink('form');
		}
		elseif ($this->returnto == 2)
		{
			$returnLink = $router->buildLink('index');
		}
		elseif ($this->returnto == 3)
		{
			$post = $contexts['post'] ?? null;
			$thread = $contexts['thread'] ?? null;

			if ($post instanceof \XF\Entity\Post && $post->Thread->canView())
			{
				$returnLink = $router->buildLink('posts', $post);
			}
			elseif ($thread instanceof \XF\Entity\Thread && $thread->canView())
			{
				$returnLink = $router->buildLink('threads', $thread);
			}
		}
		elseif ($this->returnto == 4)
		{
			$returnLink = $this->returnlink;
		}

		return $returnLink;
	}

	public function getWrappedQuestionMessage($question)
	{
		if ($this->qcolor)
		{
			return "[COLOR={$this->qcolor}]{$question}[/COLOR]";
		}

		return $question;
	}

	public function getWrappedAnswerMessage($answer)
	{
		if ($this->acolor)
		{
			return "[COLOR={$this->acolor}]{$answer}[/COLOR]";
		}

		return $answer;
	}

	public function getReportContentTypes(): array
	{
		return ['post', 'conversation_message', 'email'];
	}

	public function getExportData(): array
	{
		return [
			'posid' => $this->posid,
			'position' => htmlspecialchars($this->position),
			'node_id' => $this->node_id,
			'secnode_id' => $this->secnode_id,
			'active' => ($this->active) ? 1 : 0,
			'subject' => htmlspecialchars($this->subject),
			'email' => htmlspecialchars($this->email),
			'email_parent' => $this->email_parent,
			'inthread' => ($this->inthread) ? 1 : 0,
			'insecthread' => ($this->insecthread) ? 1 : 0,
			'posterid' => $this->posterid,
			'secposterid' => $this->secposterid,
			'bypm' => ($this->bypm) ? 1 : 0,
			'pmsender' => $this->pmsender,
			'pmdelete' => ($this->pmdelete) ? 1 : 0,
			'pmerror' => $this->pmerror,
			'pmto' => $this->pmto,
			'appid' => $this->appid,
			'prefix_ids' => implode(',', $this->prefix_ids),
			'returnto' => $this->returnto,
			'returnlink' => $this->returnlink,
			'postapproval' => ($this->postapproval) ? 1 : 0,
			'parseyesno' => ($this->parseyesno) ? 1 : 0,
			'incname' => ($this->incname) ? 1 : 0,
			'oldthread' => $this->oldthread,
			'pmapp' => ($this->pmapp) ? 1 : 0,
			'pmtext' => htmlspecialchars($this->pmtext),
			'apppromote' => ($this->apppromote) ? 1 : 0,
			'promoteto' => $this->promoteto,
			'appadd' => ($this->appadd) ? 1 : 0,
			'addto' => serialize($this->addto),
			'postpoll' => ($this->postpoll) ? 1 : 0,
			'pollpublic' => ($this->pollpublic) ? 1 : 0,
			'pollchange' => ($this->pollchange) ? 1 : 0,
			'pollquestion' => htmlspecialchars($this->pollquestion),
			'promote_type' => $this->promote_type,
			'pollclose' => $this->pollclose,
			'pollpromote' => serialize($this->pollpromote),
			'decidepromote' => $this->decidepromote,
			'removeinstant' => ($this->removeinstant) ? 1 : 0,
			'approved_title' => htmlspecialchars($this->approved_title),
			'approved_text' => htmlspecialchars($this->approved_text),
			'denied_title' => htmlspecialchars($this->denied_title),
			'denied_text' => htmlspecialchars($this->denied_text),
			'app_style' => $this->app_style,
			'user_criteria' => serialize($this->user_criteria),
			'watchthread' => ($this->watchthread) ? 1 : 0,
			'make_moderator' => $this->make_moderator,
			'instant' => ($this->instant) ? 1 : 0,
			'aboveapp' => htmlspecialchars($this->aboveapp),
			'belowapp' => htmlspecialchars($this->belowapp),
			'approved_file' => $this->approved_file,
			'normalpoll' => ($this->normalpoll) ? 1 : 0,
			'normalpublic' => ($this->normalpublic) ? 1 : 0,
			'normalchange' => ($this->normalchange) ? 1 : 0,
			'normalclose' => $this->normalclose,
			'normalquestion' => $this->normalquestion,
			'threadapp' => ($this->threadapp) ? 1 : 0,
			'threadbutton' => htmlspecialchars($this->threadbutton),
			'thanks' => htmlspecialchars($this->thanks),
			'formlimit' => $this->formlimit,
			'response' => serialize($this->response),
			'qcolor' => $this->qcolor,
			'acolor' => $this->acolor,
			'forummod' => serialize($this->forummod),
			'supermod' => serialize($this->supermod),
			'quickreply' => $this->quickreply,
			'store' => ($this->store) ? 1 : 0,
			'start' => $this->start,
			'end' => $this->end,
			'qroption' => ($this->qroption) ? 1 : 0,
			'qrbutton' => htmlspecialchars($this->qrbutton),
			'qrstarter' => ($this->qrstarter) ? 1 : 0,
			'qrforums' => serialize($this->qrforums),
			'aftererror' => htmlspecialchars($this->aftererror),
			'bbstart' => htmlspecialchars($this->bbstart),
			'bbend' => htmlspecialchars($this->bbend),
			'display_parent' => $this->display_parent,
			'display' => $this->display
		];
	}

	/************************* LIFE-CYCLE ***************************/

	protected function _postDelete()
	{
		// REMOVE THREAD BUTTON FROM FORUM NODE
		if ($this->node_id)
		{
			$update = ['snog_posid' => 0, 'snog_label' => ""];
			$this->db()->update('xf_node', $update, 'snog_posid = ?', $this->posid);
		}

		/** @var \XF\Repository\Option $optionRepo */
		$optionRepo = $this->repository('XF:Option');
		$optionRepo->updateOption('snogFormsLastUpdate', \XF::$time);
	}

	protected function _postSave()
	{
		if ($this->node_id)
		{
			$changed = false;

			// CHANGE BOTH FORM ID AND BUTTON LABEL FOR NEW THREAD BUTTON
			if ($this->isChanged('threadapp') || $this->isChanged('node_id'))
			{
				if ($this->threadapp)
				{
					// FIRST REMOVE EXISTING INFO IN CASE OF USE IN DIFFERENT NODE
					$update = ['snog_posid' => 0, 'snog_label' => ""];
					$this->db()->update('xf_node', $update, 'snog_posid = ?', $this->posid);

					// ADD FORM INFO TO NODE
					$update = ['snog_posid' => $this->posid, 'snog_label' => $this->threadbutton];
					$this->db()->update('xf_node', $update, 'node_id = ?', $this->node_id);
				}
				else
				{
					$previous = $this->getPreviousValue('node_id');

					// REMOVE FORM INFO FROM NODE
					if ($previous && $previous !== $this->node_id)
					{
						$update = ['snog_posid' => 0, 'snog_label' => ""];
						$this->db()->update('xf_node', $update, 'node_id = ?', $previous);
					}
					else
					{
						$update = ['snog_posid' => 0, 'snog_label' => ""];
						$this->db()->update('xf_node', $update, 'node_id = ? AND snog_posid = ' . $this->posid, $this->node_id);
					}
				}

				$changed = true;
			}

			// ONLY BUTTON LABEL CHANGED - CHANGE IT
			if (!$changed && $this->isChanged('threadbutton'))
			{
				if ($this->threadbutton)
				{
					// CHANGE LABEL FOR NODE
					$update = ['snog_label' => $this->threadbutton];
					$this->db()->update('xf_node', $update, 'node_id = ? AND snog_posid = ' . $this->posid, $this->node_id);
				}
				else
				{
					// REMOVE LABEL FROM NODE
					$update = ['snog_label' => ""];
					$this->db()->update('xf_node', $update, 'node_id = ?', $this->node_id);
				}
			}
		}

		if ($this->hasChanges())
		{
			/** @var \XF\Repository\Option $optionRepo */
			$optionRepo = $this->repository('XF:Option');
			$optionRepo->updateOption('snogFormsLastUpdate', \XF::$time);
		}
	}

	protected function _preSave()
	{
		// ERROR PROCESSING
		if (!$this->user_criteria)
		{
			$this->error(\XF::phrase('snog_forms_form_criteria_error'));
		}

		if (!$this->email && !$this->inthread && !$this->bypm && !$this->oldthread && !$this->qroption)
		{
			$this->error(\XF::phrase('snog_forms_report_error'));
		}

		if ($this->email)
		{
			$email_error = false;
			$addresses = explode(',', $this->email);
			foreach ($addresses as $address)
			{
				if (stristr($address, '@') === false) $email_error = true;
			}

			if ($email_error) $this->error(\XF::phrase('snog_forms_email_error'));
		}

		if ($this->inthread && !$this->node_id) $this->error(\XF::phrase('snog_forms_no_node'));

		if ($this->qroption)
		{
			if ($this->oldthread) $this->error(\XF::phrase('snog_forms_error_existing_qr'));
			if ($this->quickreply) $this->error(\XF::phrase('snog_forms_error_threadqr_qro'));
			if (!$this->qrforums) $this->error(\XF::phrase('snog_forms_error_qro_noforum'));

			if ($this->qrforums)
			{
				foreach ($this->qrforums as $forum)
				{
					$condition = '%"' . $forum . '"%';
					$finder = $this->finder('Snog\Forms:Form');

					/** @var Form $form */
					$form = $finder->where('posid', '<>', $this->posid)->where('qrforums', 'LIKE', $condition)->fetchOne();

					if ($form)
					{
						$this->error(\XF::phrase('snog_forms_error_forum_used', ['form' => $form->position]));
					}
				}
			}
		}

		if ($this->inthread && $this->oldthread)
		{
			$this->error(\XF::phrase('snog_forms_oldnew_error'));
		}

		if (($this->inthread || $this->oldthread) && $this->posterid)
		{
			/** @var \XF\Entity\User $poster */
			$poster = $this->finder('XF:User')->where('username', $this->posterid)->fetchOne();
			if (!$poster) $this->error(\XF::phrase('snog_forms_error_poster'));
		}

		if ($this->oldthread)
		{
			/** @var \XF\Entity\Thread $thread */
			$thread = $this->finder('XF:Thread')->with('Forum')->with('Forum.Node')->where('thread_id', $this->oldthread)->fetchOne();
			if (!$thread) $this->error(\XF::phrase('snog_forms_existing_error'));
		}

		if (stristr($this->qcolor, 'rgba') || stristr($this->acolor, 'rgba'))
		{
			$this->error(\XF::phrase('snog_forms_error_color'));
			return;
		}
		if ($this->quickreply && !$this->oldthread && !$this->qroption)
		{
			$this->error(\XF::phrase('snog_forms_error_quick_reply'));
		}
		if ($this->quickreply && ($this->normalpoll || $this->postpoll))
		{
			$this->error(\XF::phrase('snog_forms_error_reply_poll'));
		}

		if (($this->normalpoll || $this->postpoll) && $this->Forum && !$this->Forum->isThreadTypeCreatable('poll'))
		{
			$this->error(\XF::phrase('snog_forms_poll_is_not_creatable_in_selected_forum'));
		}

		if ($this->threadapp && !$this->node_id)
		{
			$this->error(\XF::phrase('snog_forms_no_node_button'));
		}

		if ($this->threadapp && $this->node_id)
		{
			// DB USED HERE TO AVOID ERRORS IN SETUP.PHP (LISTENERS ARE DISABLED DURING SETUP)
			$db = \XF::db();
			$node = $db->fetchRow("SELECT * FROM xf_node WHERE node_id = " . $this->node_id);

			if (!empty($node))
			{
				if ($node['node_id'] == $this->node_id && ($node['snog_posid'] > 0 && $node['snog_posid'] !== $this->posid))
				{
					$this->error(\XF::phrase('snog_forms_error_forum_button'));
				}
			}
		}

		if ($this->insecthread && !$this->secnode_id) $this->error(\XF::phrase('snog_forms_second_thread_forum_error'));

		if ($this->insecthread && $this->secposterid)
		{
			/** @var \XF\Entity\User $secPoster */
			$secPoster = $this->finder('XF:User')->where('username', $this->secposterid)->fetchOne();
			if (!$secPoster) $this->error(\XF::phrase('snog_forms_error_second_poster'));
		}

		if ($this->bypm)
		{
			if (!$this->pmto) $this->error(\XF::phrase('snog_forms_to_error'));

			$receivers = explode(',', $this->pmto);

			foreach ($receivers as $receiver)
			{
				if (trim($receiver) == $this->pmsender)
				{
					$this->error(\XF::phrase('snog_forms_error_pcsender_receiver'));
				}
			}
		}

		if ($this->pmapp && !$this->pmtext) $this->error(\XF::phrase('snog_forms_pc_text_error'));
		if ($this->returnto == 4 && !$this->returnlink) $this->error(\XF::phrase('snog_forms_link_error'));
		if ($this->apppromote && !$this->promoteto) $this->error(\XF::phrase('snog_forms_primary_error'));
		if ($this->appadd && !$this->addto) $this->error(\XF::phrase('snog_forms_addto_error'));
		if ($this->postpoll && !$this->inthread) $this->error(\XF::phrase('snog_forms_promopoll_thread_error'));
		if ($this->instant && !$this->decidepromote && !$this->pollpromote) $this->error(\XF::phrase('snog_forms_decide_error'));
		if ($this->postpoll && $this->normalpoll) $this->error(\XF::phrase('snog_forms_poll_error'));
		if ($this->postpoll && !$this->pollquestion) $this->error(\XF::phrase('snog_forms_poll_question_error'));
		if ($this->postpoll && !$this->pmerror) $this->error(\XF::phrase('snog_forms_tie_error'));
		if ($this->postpoll && $this->pmsender == $this->pmerror) $this->error(\XF::phrase('snog_forms_sender_same'));
		if ($this->normalpoll && !$this->inthread) $this->error(\XF::phrase('snog_forms_normalpoll_thread_error'));
		if ($this->normalpoll && !$this->normalquestion) $this->error(\XF::phrase('snog_forms_normal_question_error'));
		$questionCount = count($this->response);
		if ($this->normalpoll && $questionCount < 2) $this->error(\XF::phrase('snog_forms_question_count_error'));

		// USER NAME ERRORS
		if ($this->pmsender)
		{
			/** @var \XF\Entity\User $user */
			$user = $this->finder('XF:User')->where('username', $this->pmsender)->fetchOne();
			if (!$user) $this->error(\XF::phrase('snog_forms_sender_name_error'));
		}

		if ($this->pmto)
		{
			$names = explode(',', $this->pmto);
			$badNames = '';
			foreach ($names as $name)
			{
				/** @var \XF\Entity\User $user */
				$user = $this->finder('XF:User')->where('username', trim($name))->fetchOne();

				if (!$user)
				{
					if ($badNames) $badNames .= ', ';
					$badNames .= $name;
				}
			}

			if ($badNames)
			{
				$this->error(\XF::phrase('snog_forms_to_name_error', ['names' => $badNames]));
			}
		}

		if ($this->pmerror)
		{
			/** @var \XF\Entity\User $user */
			$user = $this->finder('XF:User')->where('username', $this->pmerror)->fetchOne();
			if (!$user) $this->error(\XF::phrase('snog_forms_send_ties_error'));
		}

		// REMOVE NODE ID IF BEING POSTED IN AN EXISTING THREAD
		if ($this->oldthread)
		{
			$this->node_id = 0;
		}
	}

	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_snog_forms_forms';
		$structure->shortName = 'Snog\Forms:Form';
		$structure->contentType = 'form';
		$structure->primaryKey = 'posid';
		$structure->columns = [
			'posid' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'position' => ['type' => self::STR, 'maxLength' => 100, 'required' => 'snog_forms_name_error'],
			'node_id' => ['type' => self::UINT, 'default' => 0],
			'secnode_id' => ['type' => self::UINT, 'default' => 0],
			'active' => ['type' => self::BOOL, 'default' => false],
			'email' => ['type' => self::STR, 'maxLength' => 200, 'default' => ''],
			'email_parent' => ['type' => self::UINT, 'default' => 0],
			'inthread' => ['type' => self::BOOL, 'default' => false],
			'insecthread' => ['type' => self::BOOL, 'default' => false],
			'posterid' => ['type' => self::STR, 'maxLength' => 50, 'default' => ''],
			'secposterid' => ['type' => self::STR, 'maxLength' => 50, 'default' => ''],
			'bypm' => ['type' => self::BOOL, 'default' => false],
			'pmsender' => ['type' => self::STR, 'maxLength' => 50, 'required' => 'snog_forms_sender_error'],
			'pmdelete' => ['type' => self::BOOL, 'default' => false],
			'pmerror' => ['type' => self::STR, 'maxLength' => 50, 'default' => ''],
			'pmto' => ['type' => self::STR, 'maxLength' => 200, 'default' => ''],
			'subject' => ['type' => self::STR, 'maxLength' => 150, 'required' => 'snog_forms_title_error', 'censor' => true],
			'appid' => ['type' => self::UINT, 'default' => 0],
			'prefix_ids' => ['type' => self::LIST_COMMA, 'default' => [], 'list' => ['type' => 'uint', 'unique' => true]],
			'returnto' => ['type' => self::UINT, 'default' => 2],
			'returnlink' => ['type' => self::STR, 'maxLength' => 200, 'default' => ''],
			'postapproval' => ['type' => self::BOOL, 'default' => false],
			'parseyesno' => ['type' => self::BOOL, 'default' => false],
			'incname' => ['type' => self::BOOL, 'default' => true],
			'oldthread' => ['type' => self::UINT, 'default' => 0],
			'pmapp' => ['type' => self::BOOL, 'default' => false],
			'pmtext' => ['type' => self::STR, 'default' => ''],
			'apppromote' => ['type' => self::BOOL, 'default' => false],
			'promoteto' => ['type' => self::UINT, 'default' => 0],
			'appadd' => ['type' => self::BOOL, 'default' => false],
			'addto' => ['type' => self::JSON_ARRAY, 'default' => []],
			'postpoll' => ['type' => self::BOOL, 'default' => false],
			'pollpublic' => ['type' => self::BOOL, 'default' => false],
			'pollchange' => ['type' => self::BOOL, 'default' => false],
			'pollview' => ['type' => self::BOOL, 'default' => false],
			'pollquestion' => ['type' => self::STR, 'maxLength' => 100, 'default' => ''],
			'promote_type' => ['type' => self::UINT, 'default' => 0],
			'pollclose' => ['type' => self::UINT, 'default' => 0],
			'pollpromote' => ['type' => self::JSON_ARRAY, 'default' => []],
			'decidepromote' => ['type' => self::UINT, 'default' => 0],
			'removeinstant' => ['type' => self::BOOL, 'default' => false],
			'approved_title' => ['type' => self::STR, 'maxLength' => 150, 'default' => ''],
			'approved_text' => ['type' => self::STR, 'default' => ''],
			'denied_title' => ['type' => self::STR, 'maxLength' => 150, 'default' => ''],
			'denied_text' => ['type' => self::STR, 'default' => ''],
			'app_style' => ['type' => self::UINT, 'default' => 0],
			'user_criteria' => ['type' => self::JSON_ARRAY, 'default' => []],
			'watchthread' => ['type' => self::BOOL, 'default' => false],
			'make_moderator' => ['type' => self::UINT, 'default' => 1],
			'instant' => ['type' => self::BOOL, 'default' => false],
			'aboveapp' => ['type' => self::STR, 'default' => ''],
			'belowapp' => ['type' => self::STR, 'default' => ''],
			'approved_file' => ['type' => self::STR, 'maxLength' => 255, 'default' => ''],
			'normalpoll' => ['type' => self::BOOL, 'default' => false],
			'normalpublic' => ['type' => self::BOOL, 'default' => false],
			'normalchange' => ['type' => self::BOOL, 'default' => false],
			'normalview' => ['type' => self::BOOL, 'default' => false],
			'normalclose' => ['type' => self::UINT, 'default' => 0],
			'normalquestion' => ['type' => self::STR, 'maxLength' => 100, 'default' => ''],
			'threadapp' => ['type' => self::BOOL, 'default' => false],
			'threadbutton' => ['type' => self::STR, 'maxLength' => 50, 'default' => ''],
			'thanks' => ['type' => self::STR, 'maxLength' => 200, 'default' => ''],
			'formlimit' => ['type' => self::UINT, 'default' => 0],
			'response' => ['type' => self::JSON_ARRAY, 'default' => []],
			'qcolor' => ['type' => self::STR, 'maxLength' => 20, 'default' => ''],
			'acolor' => ['type' => self::STR, 'maxLength' => 20, 'default' => ''],
			'forummod' => ['type' => self::JSON_ARRAY, 'default' => []],
			'supermod' => ['type' => self::JSON_ARRAY, 'default' => []],
			'quickreply' => ['type' => self::UINT, 'default' => 0],
			'store' => ['type' => self::BOOL, 'default' => false],
			'start' => ['type' => self::UINT, 'default' => 0],
			'end' => ['type' => self::UINT, 'default' => 0],
			'qroption' => ['type' => self::BOOL, 'default' => false],
			'qrbutton' => ['type' => self::STR, 'maxLength' => 50, 'default' => ''],
			'qrstarter' => ['type' => self::BOOL, 'default' => false],
			'qrforums' => ['type' => self::JSON_ARRAY, 'default' => []],
			'aftererror' => ['type' => self::STR, 'maxLength' => 200, 'default' => ''],
			'bbstart' => ['type' => self::STR, 'maxLength' => 200, 'default' => ''],
			'bbend' => ['type' => self::STR, 'maxLength' => 200, 'default' => ''],
			'display_parent' => ['type' => self::UINT, 'default' => 0],
			'display' => ['type' => self::UINT, 'default' => 0],
			'minimum_attachments' => ['type' => self::UINT, 'default' => 0, 'min' => 0],
			'submit_count' => ['type' => self::UINT, 'default' => 0],
			'is_public_visible' => ['type' => self::BOOL, 'default' => false],
			'cooldown' => ['type' => self::INT, 'default' => 0],
		];

		$structure->getters = [];

		$structure->relations = [
			'Type' => [
				'entity' => 'Snog\Forms:Type',
				'type' => self::TO_ONE,
				'conditions' => 'appid',
				'primary' => true
			],
			'Questions' => [
				'entity' => 'Snog\Forms:Question',
				'type' => self::TO_MANY,
				'conditions' => 'posid',
				'primary' => false,
				'order' => ['display', 'ASC']
			],
			'Forum' => [
				'entity' => 'XF:Forum',
				'type' => self::TO_ONE,
				'conditions' => 'node_id',
				'primary' => true
			],
			'SecondForum' => [
				'entity' => 'XF:Forum',
				'type' => self::TO_ONE,
				'conditions' => [['node_id', '=', '$secnode_id']],
				'primary' => true
			],
			'PosterUser' => [
				'entity' => 'XF:User',
				'type' => self::TO_ONE,
				'conditions' => [['username', '=', '$posterid']],
				'primary' => true
			],
			'SecondaryPosterUser' => [
				'entity' => 'XF:User',
				'type' => self::TO_ONE,
				'conditions' => [['username', '=', '$secposterid']],
				'primary' => true
			],
			'ConversationUser' => [
				'entity' => 'XF:User',
				'type' => self::TO_ONE,
				'conditions' => [['username', '=', '$pmsender']],
				'primary' => true
			],
		];

		return $structure;
	}
}