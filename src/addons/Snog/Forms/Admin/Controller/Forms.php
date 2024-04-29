<?php

namespace Snog\Forms\Admin\Controller;

use Snog\Forms\Entity\Form;
use Snog\Forms\Entity\Question;
use XF\Admin\Controller\AbstractController;
use XF\Html\Renderer\BbCode;
use XF\Mvc\ParameterBag;

class Forms extends AbstractController
{
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('snogFormsAdmin');
	}

	public function actionMain()
	{
		$this->setSectionContext('snog_forms');
		return $this->view('Snog:Forms', 'snog_forms_main');
	}

	public function actionIndex()
	{
		$forms = $this->finder('Snog\Forms:Form')->order('display', 'ASC')->fetch();
		$viewParams = ['forms' => $forms];
		return $this->view('Snog:Forms\Forms', 'snog_forms_form_list', $viewParams);
	}

	public function actionAdd()
	{
		/** @var Form $form */
		$form = $this->em()->create('Snog\Forms:Form');
		$formCount = 0;

		/** @var Form $lastForm */
		$lastForm = $this->finder('Snog\Forms:Form')->order('display', 'ASC')->fetchOne();

		if ($lastForm)
		{
			$formCount = $lastForm->display + 1;
		}

		return $this->formAddEdit($form, $formCount);
	}

	public function formAddEdit(Form $form, $formDisplay = 0)
	{
		/** @var \XF\Repository\Moderator $moderatorRepo */
		$moderatorRepo = $this->repository('XF:Moderator');
		$moderatorPermissionData = $moderatorRepo->getModeratorPermissionData('node');

		$data['start'] = date('Y', time());
		$data['end'] = $data['start'] + 25;
		$data['hours'] = ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09', 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23];
		$data['minutes'] = ['00', '05', 10, 15, 20, 25, 30, 35, 40, 45, 50, 55];
		$data['starthour'] = '00';
		$data['startminute'] = '00';
		$data['endhour'] = '00';
		$data['endminute'] = '00';

		// SET NEXT DISPLAY VALUE IF NEW FORM
		if (!$form->display)
		{
			$form->display = $formDisplay;
		}

		// TIMEZONE TO GUEST TIMEZONE
		// ENSURES FORM STARTS/STOPS AT THE SAME TIME FOR EVERYONE
		date_default_timezone_set($this->options()->guestTimeZone);

		if ($form->start)
		{
			$data['starthour'] = date('H', $form->start);
			$data['startminute'] = date('i', $form->start);
		}

		if ($form->end)
		{
			$data['endhour'] = date('H', $form->end);
			$data['endminute'] = date('i', $form->end);
		}

		/** @var \XF\Repository\Style $styleRepo */
		$styleRepo = $this->repository('XF:Style');

		/** @var \XF\Repository\Node $nodeRepo */
		$nodeRepo = $this->repository('XF:Node');

		/** @var \Snog\Forms\Repository\Type $typeRepo */
		$typeRepo = $this->repository('Snog\Forms:Type');

		/** @var \XF\Repository\UserGroup $userGroupRepo */
		$userGroupRepo = $this->repository('XF:UserGroup');

		$viewParams = [
			'form' => $form,
			'data' => $data,
			'types' => $typeRepo->findTypesForList()->fetch(),
			'userGroups' => $userGroupRepo->findUserGroupsForList()->fetch(),
			'nodeTree' => $nodeRepo->createNodeTree($nodeRepo->getFullNodeList()),
			'availablePrefixes' => $this->getPrefixes(),
			'interfaceGroups' => $moderatorPermissionData['interfaceGroups'],
			'globalPermissions' => $moderatorPermissionData['globalPermissions'],
			'contentPermissions' => $moderatorPermissionData['contentPermissions'],
			'userCriteriaRendered' => $this->buildPermissionDisplay($form),
			'styleTree' => $styleRepo->getStyleTree(false)
		];

		return $this->view('Snog:Forms\Forms', 'snog_forms_form_edit', $viewParams);
	}

	public function actionEdit(ParameterBag $params)
	{
		$form = $this->assertFormExists($params['posid']);
		return $this->formAddEdit($form);
	}

	public function actionCopy(ParameterBag $params)
	{
		$formCount = 0;
		$forms = $this->finder('Snog\Forms:Form')->order('display', 'ASC')->fetch();

		/** @var Form $lastForm */
		$lastForm = $forms ? $forms->last() : null;

		if ($lastForm)
		{
			$formCount = $lastForm->display + 1;
		}

		/** @var Form $form */
		$form = $this->em()->find('Snog\Forms:Form', $params['posid']);

		/** @var \XF\Repository\Moderator $moderatorRepo */
		$moderatorRepo = $this->repository('XF:Moderator');
		$moderatorPermissionData = $moderatorRepo->getModeratorPermissionData('node');

		$form->set('posid', 0, ['forceSet' => true]);
		$form->position = 'Copy Of: ' . $form->position;
		$form->active = false;
		$form->display = $formCount;

		/** @var \XF\Repository\Style $styleRepo */
		$styleRepo = $this->repository('XF:Style');

		/** @var \XF\Repository\Node $nodeRepo */
		$nodeRepo = $this->repository('XF:Node');

		/** @var \Snog\Forms\Repository\Type $typeRepo */
		$typeRepo = $this->repository('Snog\Forms:Type');

		/** @var \XF\Repository\UserGroup $userGroupRepo */
		$userGroupRepo = $this->repository('XF:UserGroup');

		$viewParams = [
			'form' => $form,
			'action' => 'edit',
			'copyform' => $params['posid'],
			'types' => $typeRepo->findTypesForList()->fetch(),
			'userGroups' => $userGroupRepo->findUserGroupsForList()->fetch(),
			'nodeTree' => $nodeRepo->createNodeTree($nodeRepo->getFullNodeList()),
			'availablePrefixes' => $this->getPrefixes(),
			'interfaceGroups' => $moderatorPermissionData['interfaceGroups'],
			'globalPermissions' => $moderatorPermissionData['globalPermissions'],
			'contentPermissions' => $moderatorPermissionData['contentPermissions'],
			'userCriteriaRendered' => $this->buildPermissionDisplay($form),
			'styleTree' => $styleRepo->getStyleTree(false)
		];

		return $this->view('Snog:Forms\Forms', 'snog_forms_form_edit', $viewParams);
	}

	public function actionSave(ParameterBag $params)
	{
		if ($params->posid)
		{
			$modifiedForm = $this->assertFormExists($params->posid);
		}
		else
		{
			/** @var Form $modifiedForm */
			$modifiedForm = $this->em()->create('Snog\Forms:Form');
		}

		$this->formSaveProcess($modifiedForm)->run();

		$copyId = $this->filter('copyform', 'uint');

		// FORM IS BEING COPIED - COPY QUESTIONS FROM ORIGINAL FORM
		if ($copyId)
		{
			/** @var Question[] $originalQuestions */
			$originalQuestions = $this->finder('Snog\Forms:Question')->where('posid', $copyId)->fetch();

			$newParents = [];

			foreach ($originalQuestions as $originalQuestion)
			{
				/** @var Question $newquestion */
				$newquestion = $this->em()->create('Snog\Forms:Question');

				$newquestion->posid = $modifiedForm->posid;
				$newquestion->text = $originalQuestion->text;
				$newquestion->description = $originalQuestion->description;
				$newquestion->type = $originalQuestion->type;
				$newquestion->error = $originalQuestion->error;
				$newquestion->expected = $originalQuestion->expected;
				$newquestion->display = $originalQuestion->display;
				$newquestion->display_parent = $originalQuestion->display_parent;
				$newquestion->regex = $originalQuestion->regex;
				$newquestion->regexerror = $originalQuestion->regexerror;
				$newquestion->defanswer = $originalQuestion->defanswer;
				$newquestion->questionpos = $originalQuestion->questionpos;
				$newquestion->showquestion = $originalQuestion->showquestion;
				$newquestion->showunanswered = $originalQuestion->showunanswered;
				$newquestion->inline = $originalQuestion->inline;
				$newquestion->format = $originalQuestion->format;
				$newquestion->placeholder = $originalQuestion->placeholder;

				if ($originalQuestion->hasconditional)
				{
					$newquestion->hasconditional = $originalQuestion->hasconditional;
				}

				// ASSIGN NEW PARENT ID TO CHILD
				if ($originalQuestion->conditional)
				{
					foreach ($newParents as $newParent)
					{
						if ($newParent['oldId'] == $originalQuestion->conditional)
						{
							$newquestion->conditional = $newParent['newId'];
						}
					}
				}

				$newquestion->conanswer = $originalQuestion->conanswer;
				$newquestion->save();

				// BUILD LIST OF OLD PARENT QUESTIONS
				if ($originalQuestion->hasconditional)
				{
					$newParents[] = [
						'oldId' => $originalQuestion->questionid,
						'newId' => $newquestion->getEntityId()
					];
				}
			}

			// GET PARENT QUESTIONS AND REBUILD CHILD LIST
			if (!empty($newParents))
			{
				$findQuestions = '';

				foreach ($newParents as $newParent)
				{
					if ($findQuestions) $findQuestions .= ',';
					$findQuestions .= $newParent['newId'];
				}

				$questionFinder = $this->finder('Snog\Forms:Question');
				$questionFinder->whereSql("FIND_IN_SET(" . $questionFinder->columnSqlName('questionid') . ", " . $questionFinder->quote($findQuestions) . ")");

				/** @var Question[] $changeParents */
				$changeParents = $questionFinder->fetch();

				foreach ($changeParents as $changeParent)
				{
					$newChildList = [];

					/** @var Question[] $newChildren */
					$newChildren = $this->finder('Snog\Forms:Question')->where('conditional', $changeParent->questionid)->fetch();

					foreach ($newChildren as $newChild)
					{
						$newChildList[] = $newChild->questionid;
					}

					$changeParent->hasconditional = $newChildList;
					$changeParent->save();
				}
			}
		}

		return $this->redirect($this->buildLink('form-forms'));
	}

	protected function formSaveProcess(Form $modifiedForm)
	{
		$form = $this->formAction();
		$input = $this->filter([
			'position' => 'str',
			'node_id' => 'uint',
			'secnode_id' => 'uint',
			'active' => 'bool',
			'subject' => 'str',
			'email' => 'str',
			'email_parent' => 'uint',
			'inthread' => 'bool',
			'insecthread' => 'bool',
			'posterid' => 'str',
			'secposterid' => 'str',
			'bypm' => 'bool',
			'pmsender' => 'str',
			'pmdelete' => 'bool',
			'pmerror' => 'str',
			'pmto' => 'str',
			'appid' => 'uint',
			'returnto' => 'uint',
			'returnlink' => 'str',
			'postapproval' => 'bool',
			'parseyesno' => 'bool',
			'incname' => 'bool',
			'oldthread' => 'uint',
			'pmapp' => 'bool',
			'pmtext' => 'str',
			'apppromote' => 'bool',
			'promoteto' => 'uint',
			'appadd' => 'bool',
			'addto' => 'array',
			'postpoll' => 'bool',
			'pollpublic' => 'bool',
			'pollchange' => 'bool',
			'pollview' => 'bool',
			'pollquestion' => 'str',
			'promote_type' => 'uint',
			'pollclose' => 'uint',
			'pollpromote' => 'array',
			'decidepromote' => 'uint',
			'removeinstant' => 'bool',
			'approved_title' => 'str',
			'approved_text' => 'str',
			'denied_title' => 'str',
			'denied_text' => 'str',
			'app_style' => 'uint',
			'user_criteria' => 'array',
			'watchthread' => 'bool',
			'make_moderator' => 'uint',
			'instant' => 'bool',
			'aboveapp' => 'str',
			'belowapp' => 'str',
			'approved_file' => 'str',
			'normalpoll' => 'bool',
			'normalpublic' => 'bool',
			'normalchange' => 'bool',
			'normalview' => 'bool',
			'normalclose' => 'uint',
			'normalquestion' => 'str',
			'threadapp' => 'bool',
			'threadbutton' => 'str',
			'thanks' => 'str',
			'formlimit' => 'uint',
			'response' => 'array',
			'qcolor' => 'str',
			'acolor' => 'str',
			'forummod' => 'array',
			'supermod' => 'array',
			'quickreply' => 'bool',
			'store' => 'bool',
			'start' => 'str',
			'end' => 'str',
			'qroption' => 'bool',
			'qrbutton' => 'str',
			'qrstarter' => 'bool',
			'qrforums' => 'array',
			'aftererror' => 'str',
			'bbstart' => 'str',
			'bbend' => 'str',
			'display' => 'uint',
			'minimum_attachments' => 'uint',
			'is_public_visible' => 'bool',
			'cooldown' => 'int',
		]);

		$addOns = $this->app()->container('addon.cache');
		$isMultiPrefix = isset($addOns['SV/MultiPrefix']);

		$input['prefix_ids'] = $isMultiPrefix
			? $this->filter('prefix_ids', 'array-uint')
			: [$this->filter('prefix_id', 'uint')];

		// CHECK REPORT TITLE FOR QUESTION ANSWERS
		preg_match_all('/({A\d+})/', $input['subject'], $titleAnswers);
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

		// VERIFY QUESTIONS FOR TITLE ARE REQUIRED TO BE ANSWERED
		if (!empty($questionIds) && $modifiedForm->posid)
		{
			/** @var \XF\Mvc\Entity\AbstractCollection|Question[] $questions */
			$questions = $this->finder('Snog\Forms:Question')
				->where('posid', $modifiedForm->posid)
				->order('display', 'ASC')
				->fetch();

			$questionArray = $questions->toArray();

			if (!empty($questionArray))
			{
				$errors = '';

				// CHECK TO BE SURE QUESTIONS EXIST AND MEET REPORT TITLE REQUIREMENTS
				foreach ($questions as $question)
				{
					if (in_array($question->display, $questionIds))
					{
						// NOT A REQUIRED QUESTION - THAT'S AN ERROR
						if (!$question->error)
						{
							$errors .= \XF::phrase('snog_forms_error_title_answer', ['number' => $question->display]) . "<br />";
						}

						// NOT A TYPE THAT CAN BE USED IN REPORT TITLE - THAT'S AN ERROR
						if (!$question->canUsedForReportTitle())
						{
							$errors .= \XF::phrase('snog_forms_error_title_type', ['number' => $question->display]) . "<br />";
						}

						// REMOVE CURRENT QUESTION FROM TITLE SEARCH
						if (($key = array_search($question->display, $questionIds)) !== false)
						{
							unset($questionIds[$key]);
						}
					}
				}

				// NON-EXISTENT QUESTIONS ARE IN REPORT TITLE - THROW ERROR
				if (!empty($questionIds))
				{
					foreach ($questionIds as $questionId)
					{
						$errors .= \XF::phrase('snog_forms_error_title_not_exist', ['number' => $questionId]) . "<br />";
					}
				}

				if ($errors)
				{
					throw $this->exception($this->notFound($errors));
				}
			}
		}
		else
		{
			// NO QUESTIONS DEFINED BUT ANSWERS IN TITLE - THROW ERROR
			if (!empty($questionIds))
			{
				throw $this->exception($this->notFound(\XF::phrase('snog_forms_error_title_assign')));
			}
		}

		$timevalues = $this->filter([
			'starthour' => 'str',
			'startminute' => 'str',
			'endhour' => 'str',
			'endminute' => 'str',
		]);

		// THREAD BUTTON REMOVED FROM FORM - REMOVE FROM FORUM NODE
		if ($modifiedForm->node_id && !$input['threadapp'])
		{
			$update = ['snog_posid' => 0, 'snog_label' => ""];
			\XF::db()->update('xf_node', $update, 'snog_posid = ?', $modifiedForm->posid);
		}

		// FORM LIMIT REMOVED - RESET ALL USER COUNTS
		if ($modifiedForm->formlimit && !$input['formlimit'])
		{
			$userFinder = $this->finder('XF:User');

			/** @var \Snog\Forms\XF\Entity\User[] $users */
			$users = $userFinder
				->where('snog_forms', 'LIKE', $userFinder->escapeLike('"posid":' . $modifiedForm->posid . ',','%?%'))
				->fetch();

			foreach ($users as $name)
			{
				$name->updateAdvancedFormsSerials($modifiedForm->posid);
				if ($name->isChanged('snog_forms'))
				{
					$name->save(false, false);
				}
			}
		}

		// THERE IS AN EXTRA RESPONSE SENT BY XF ITSELF - CLEAR IT
		foreach ($input['response'] as $key => $response)
		{
			if (!$response) unset($input['response'][$key]);
		}

		// TIMEZONE TO GUEST TIMEZONE
		// ENSURES FORM STARTS/STOPS AT THE SAME TIME FOR EVERYONE
		date_default_timezone_set($this->options()->guestTimeZone);

		if ($input['start'])
		{
			$input['start'] = strtotime($input['start'] . ' ' . $timevalues['starthour'] . ':' . $timevalues['startminute']);
		}

		if ($input['end'])
		{
			$input['end'] = strtotime($input['end'] . ' ' . $timevalues['endhour'] . ':' . $timevalues['endminute']);
		}

		$htmlInput = $this->filter([
			'aboveapp_html' => 'str',
			'belowapp_html' => 'str',
			'pmtext_html' => 'str',
			'approved_text_html' => 'str',
			'denied_text_html' => 'str'
		]);

		if ($htmlInput['aboveapp_html'])
		{
			$input['aboveapp'] = BbCode::renderFromHtml($htmlInput['aboveapp_html']);
		}
		if ($htmlInput['belowapp_html'])
		{
			$input['belowapp'] = BbCode::renderFromHtml($htmlInput['belowapp_html']);
		}
		if ($htmlInput['pmtext_html'])
		{
			$input['pmtext'] = BbCode::renderFromHtml($htmlInput['pmtext_html']);
		}
		if ($htmlInput['approved_text_html'])
		{
			$input['approved_text'] = BbCode::renderFromHtml($htmlInput['approved_text_html']);
		}
		if ($htmlInput['denied_text_html'])
		{
			$input['denied_text'] = BbCode::renderFromHtml($htmlInput['denied_text_html']);
		}
		if (empty($input['user_criteria']['connected_accounts']['data']))
		{
			unset($input['user_criteria']['connected_accounts']);
		}
		
		$form->basicEntitySave($modifiedForm, $input);

		return $form;
	}

	public function actionDelete(ParameterBag $params)
	{
		$form = $this->assertFormExists($params->posid);
		$posid = $form->posid;

		if ($this->isPost())
		{
			$form->delete();

			/** @var \Snog\Forms\Repository\Question $questionRepo */
			$questionRepo = $this->repository('Snog\Forms:Question');
			$questionRepo->deleteQuestions($posid);

			return $this->redirect($this->buildLink('form-forms'));
		}

		$viewParams = ['form' => $form];
		return $this->view('Snog:Forms\Forms', 'snog_forms_confirm', $viewParams);
	}

	public function actionReset(ParameterBag $params)
	{
		$form = $this->assertFormExists($params->posid);

		if ($this->isPost())
		{
			$userFinder = $this->finder('XF:User');

			/** @var \Snog\Forms\XF\Entity\User[] $users */
			$users = $userFinder
				->where('snog_forms', 'LIKE', $userFinder->escapeLike('"posid":' . $form->posid . ',','%?%'))
				->fetch();

			foreach ($users as $name)
			{
				$name->updateAdvancedFormsSerials($params->posid);
				if ($name->isChanged('snog_forms'))
				{
					$name->save(false, false);
				}
			}

			return $this->redirect($this->buildLink('form-forms'), 'Done');
		}

		$viewParams = ['reset' => $form];
		return $this->view('Snog:Forms\Forms', 'snog_forms_confirm', $viewParams);
	}

	public function actionResetone(ParameterBag $params)
	{
		$form = $this->assertFormExists($params->posid);

		if ($this->isPost())
		{
			$input = $this->filter(['resetuser' => 'str']);

			/** @var \Snog\Forms\XF\Entity\User $name */
			$name = $this->em()->findOne('XF:User', ['username', '=', $input['resetuser']]);

			if (!isset($name->user_id))
			{
				return $this->error(\XF::phrase('unknown_user'));
			}

			$name->updateAdvancedFormsSerials($params->posid);
			if ($name->isChanged('snog_forms'))
			{
				$name->save(false, false);
			}
			return $this->redirect($this->buildLink('form-forms'), 'Done');
		}

		$viewParams = ['resetone' => $form];
		return $this->view('Snog:Forms\Forms', 'snog_forms_confirm', $viewParams);
	}

	public function actionSort()
	{
		$forms = $this->finder('Snog\Forms:Form')->order('display', 'ASC')->fetch();

		/** @var \Snog\Forms\Repository\Form $formRepo */
		$formRepo = $this->repository('Snog\Forms:Form');
		$formList = $formRepo->createFormTree($forms);

		if ($this->isPost())
		{
			/** @var \XF\ControllerPlugin\Sort $sorter */
			$sorter = $this->plugin('XF:Sort');

			$options = [
				'orderColumn' => 'display',
				'jump' => 1,
				'preSaveCallback' => null
			];

			$sortTree = $sorter->buildSortTree($this->filter('forms', 'json-array'));
			$sorter->sortTree($sortTree, $formList->getAllData(), 'display_parent', $options);

			return $this->redirect($this->buildLink('form-forms'));
		}

		$viewParams = ['formList' => $formList];
		return $this->view('Snog:Forms\Sort', 'snog_forms_form_order', $viewParams);
	}

	/**
	 * @param $id
	 * @param null $with
	 * @return Form|\XF\Mvc\Entity\Entity
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertFormExists($id, $with = null)
	{
		return $this->assertRecordExists('Snog\Forms:Form', $id, $with, 'snog_forms_form_not_found');
	}

	protected function getPrefixes()
	{
		$prefixFinder = $this->finder('XF:ThreadPrefix');
		$availablePrefixes = $prefixFinder->order('materialized_order')->fetch();
		return $availablePrefixes->pluckNamed('title', 'prefix_id');
	}

	protected function buildPermissionDisplay(Form $entity)
	{
		// BUILD USER CRITERIA HELPER FOR A SINGLE PAGE (NO TABS)
		$userCriteria = $this->app->criteria('XF:User', $entity->user_criteria);
		$xfTemplater = $this->app->templater();
		$xfTemplater->addDefaultParam('xf', $this->app->getGlobalTemplateData());

		// ADDED FOR TH QA FORUMS COMPATIBILITY

		/** @var \XF\Repository\Node $nodeRepo */
		$nodeRepo = $this->repository('XF:Node');

		$userCriteriaRendered = $xfTemplater->renderMacro(
			'admin:helper_criteria',
			'Snog_user_page', [
				'criteria' => $userCriteria->getCriteriaForTemplate(),
				'data' => $userCriteria->getExtraTemplateData(),
				'th_nodeTree' => $nodeRepo->createNodeTree($nodeRepo->getFullNodeList(null, 'NodeType')),
			]
		);

		return $userCriteriaRendered;
	}
}