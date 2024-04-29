<?php

namespace Snog\Forms\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

class Types extends AbstractController
{
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('snogFormsAdmin');
	}

	public function actionIndex()
	{
		/** @var \XF\Mvc\Entity\AbstractCollection|\Snog\Forms\Entity\Type[] $types */
		$types = $this->finder('Snog\Forms:Type')->order('display', 'ASC')->fetch();
		$viewParams = ['types' => $types->toArray()];
		return $this->view('Snog:Forms\Types', 'snog_forms_type_list', $viewParams);
	}

	public function actionAdd()
	{
		/** @var \Snog\Forms\Entity\Type $type */
		$type = $this->em()->create('Snog\Forms:Type');
		$typeCount = 0;

		/** @var \Snog\Forms\Entity\Type $lastType */
		$lastType = $this->finder('Snog\Forms:Type')->order('display', 'ASC')->fetchOne();

		if ($lastType)
		{
			$typeCount = $lastType->display + 1;
		}

		return $this->typeAddEdit($type, $typeCount);
	}

	public function typeAddEdit(\Snog\Forms\Entity\Type $type, $typeDisplay = 0)
	{
		$userCriteriaRendered = $this->buildPermissionDisplay($type);

		// SET NEXT DISPLAY VALUE IF NEW TYPE
		if (!$type->display)
		{
			$type->display = $typeDisplay;
		}

		$viewParams = [
			'type' => $type,
			'userCriteriaRendered' => $userCriteriaRendered,
		];

		return $this->view('Snog:Forms\Types', 'snog_forms_type_edit', $viewParams);
	}

	public function actionEdit(ParameterBag $params)
	{
		$type = $this->assertTypeExists($params['appid']);
		return $this->typeAddEdit($type);
	}

	public function actionCopy(ParameterBag $params)
	{
		$typeCount = 0;

		/** @var \Snog\Forms\Entity\Type $lastType */
		$lastType = $types = $this->finder('Snog\Forms:Type')->order('display', 'ASC')->fetchOne();

		if (!$lastType)
		{
			$typeCount = $lastType->display + 1;
		}

		/** @var \Snog\Forms\Entity\Type $typeEntity */
		$typeEntity = $this->em()->find('Snog\Forms:Type', ['appid', $params['appid']]);
		$type = $typeEntity->toArray();
		$type['appid'] = 0;
		$type['type'] = 'Copy Of: ' . $type['type'];
		$type['active'] = false;
		$type['display'] = $typeCount;
		$userCriteriaRendered = $this->buildPermissionDisplay($typeEntity);

		$viewParams = [
			'type' => $type,
			'action' => 'edit',
			'userCriteriaRendered' => $userCriteriaRendered,
		];

		return $this->view('Snog:Forms\Types', 'snog_forms_type_edit', $viewParams);
	}

	public function actionSave(ParameterBag $params)
	{
		if ($params->appid)
		{
			$modifiedType = $this->assertTypeExists($params->appid);
		}
		else
		{
			/** @var \Snog\Forms\Entity\Type $modifiedType */
			$modifiedType = $this->em()->create('Snog\Forms:Type');
		}

		$this->typeSaveProcess($modifiedType)->run();
		return $this->redirect($this->buildLink('form-types', null));
	}

	protected function typeSaveProcess(\Snog\Forms\Entity\Type $modifiedType)
	{
		$type = $this->formAction();
		$input = $this->filter([
			'type' => 'str',
			'active' => 'bool',
			'sidebar' => 'bool',
			'navtab' => 'bool',
			'user_criteria' => 'array',
			'display' => 'uint'
		]);

		if (empty($input['user_criteria']['connected_accounts']['data']))
		{
			unset($input['user_criteria']['connected_accounts']);
		}

		$type->basicEntitySave($modifiedType, $input);
		return $type;
	}

	public function actionDelete(ParameterBag $params)
	{
		$type = $this->assertTypeExists($params->appid);

		if ($this->isPost())
		{
			$type->delete();
			return $this->redirect($this->buildLink('form-types'));
		}

		$viewParams = ['type' => $type];
		return $this->view('Snog:Forms\Types', 'snog_forms_confirm', $viewParams);
	}

	public function actionSort(ParameterBag $params)
	{
		$types = $this->finder('Snog\Forms:Type')->order('display', 'ASC')->fetch();

		/** @var \Snog\Forms\Repository\Type $typeRepo */
		$typeRepo = $this->repository('Snog\Forms:Type');
		$typeList = $typeRepo->createTypeTree($types);

		if ($this->isPost())
		{
			/** @var \XF\ControllerPlugin\Sort $sorter */
			$sorter = $this->plugin('XF:Sort');

			$options = [
				'orderColumn' => 'display',
				'jump' => 1,
				'preSaveCallback' => null
			];

			$sortTree = $sorter->buildSortTree($this->filter('types', 'json-array'));
			$sorter->sortTree($sortTree, $typeList->getAllData(), 'display_parent', $options);

			return $this->redirect($this->buildLink('form-types'));
		}

		$viewParams = ['typeList' => $typeList];
		return $this->view('Snog:Forms\Sort', 'snog_forms_type_order', $viewParams);
	}

	/**
	 * @param $id
	 * @param null $with
	 * @return \Snog\Forms\Entity\Type|\XF\Mvc\Entity\Entity
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertTypeExists($id, $with = null)
	{
		return $this->assertRecordExists('Snog\Forms:Type', $id, $with, 'snog_forms_type_not_found');
	}

	protected function buildPermissionDisplay($entity)
	{
		// BUILD USER CRITERIA HELPER TO A SINGLE PAGE (NO TABS)
		$userCriteria = $this->app->criteria('XF:User', $entity->user_criteria);
		$xfTemplater = $this->app->templater();
		$xfTemplater->addDefaultParam('xf', $this->app->getGlobalTemplateData());

		$userCriteriaRendered = $xfTemplater->renderMacro(
			'admin:helper_criteria',
			'Snog_user_page', [
				'criteria' => $userCriteria->getCriteriaForTemplate(),
				'data' => $userCriteria->getExtraTemplateData()
			]
		);

		return $userCriteriaRendered;
	}
}