<?php

namespace Snog\Forms;


use XF\Mvc\Entity\Entity;

class Listener
{
	public static function entityThreadDelete(Entity $entity)
	{
		/** @var \Snog\Forms\XF\Entity\Post $entity */
		if (isset($entity->Promotions))
		{
			$promotion = $entity->Promotions;
			if ($promotion) $promotion->delete();
		}
	}

	public static function entityPostDelete(Entity $entity)
	{
		/** @var \Snog\Forms\XF\Entity\Post $entity */
		if (isset($entity->Promotions))
		{
			$promotion = $entity->Promotions;
			if ($promotion) $promotion->delete();
		}
	}

	public static function entityPollDelete(Entity $entity)
	{
		/** @var \XF\Entity\Poll $entity */
		$pollId = $entity->poll_id;
		$db = \XF::db();
		$db->delete('xf_snog_forms_promotions', 'poll_id = ?', $pollId);
	}

	public static function userEntityPostDelete(Entity $entity)
	{
		/** @var \XF\Entity\User $entity */
		$userId = $entity->user_id;
		$db = \XF::db();
		$db->delete('xf_snog_forms_promotions', 'user_id = ?', $userId);
	}

	/**
	 * Allows direct modification of the Entity structure.
	 *
	 * Event hint: Fully qualified name of the root class that was called.
	 *
	 * @param \XF\Mvc\Entity\Manager $em Entity Manager object.
	 * @param \XF\Mvc\Entity\Structure $structure Entity Structure object.
	 */
	public static function userEntityStructure(\XF\Mvc\Entity\Manager $em, \XF\Mvc\Entity\Structure &$structure)
	{
		$structure->columns['snog_forms'] = ['type' => Entity::JSON_ARRAY, 'default' => []];
	}


	/**
	 * Allows direct modification of the Entity structure.
	 *
	 * Event hint: Fully qualified name of the root class that was called.
	 *
	 * @param \XF\Mvc\Entity\Manager $em Entity Manager object.
	 * @param \XF\Mvc\Entity\Structure $structure Entity Structure object.
	 */
	public static function nodeEntityStructure(\XF\Mvc\Entity\Manager $em, \XF\Mvc\Entity\Structure &$structure)
	{
		$structure->columns['snog_posid'] = ['type' => Entity::UINT, 'default' => 0];
		$structure->columns['snog_label'] = ['type' => Entity::STR, 'maxLength' => 30, 'default' => ''];
	}


	/**
	 * Allows direct modification of the Entity structure.
	 *
	 * Event hint: Fully qualified name of the root class that was called.
	 *
	 * @param \XF\Mvc\Entity\Manager $em Entity Manager object.
	 * @param \XF\Mvc\Entity\Structure $structure Entity Structure object.
	 */
	public static function threadEntityStructure(\XF\Mvc\Entity\Manager $em, \XF\Mvc\Entity\Structure &$structure)
	{
		$structure->relations += [
			'Promotions' => [
				'entity' => 'Snog\Forms:Promotion',
				'type' => Entity::TO_ONE,
				'conditions' => 'thread_id',
				'primary' => false
			],
			'Form' => [
				'entity' => 'Snog\Forms:Form',
				'type' => Entity::TO_ONE,
				'conditions' => [
					['oldthread', '>', '0'],
					['oldthread', '=', '$thread_id']],
				'primary' => false
			]
		];
	}

	/**
	 * Allows direct modification of the Entity structure.
	 *
	 * Event hint: Fully qualified name of the root class that was called.
	 *
	 * @param \XF\Mvc\Entity\Manager $em Entity Manager object.
	 * @param \XF\Mvc\Entity\Structure $structure Entity Structure object.
	 */
	public static function postEntityStructure(\XF\Mvc\Entity\Manager $em, \XF\Mvc\Entity\Structure &$structure)
	{
		$structure->relations['Promotions'] = [
			'entity' => 'Snog\Forms:Promotion',
			'type' => Entity::TO_ONE,
			'conditions' => 'post_id',
			'primary' => true
		];
	}


	/**
	 * Called at the end of the the Public \XF\Pub\App object startup process.
	 *
	 * @param \XF\Pub\App $app Public App object.
	 */
	public static function appPubStartEnd(\XF\Pub\App $app)
	{
		/** @var \Snog\Forms\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		if ($visitor->canViewAdvancedForms())
		{
			$session = $app->session();
			$sessionFormsCounts = $session->snogFormsCount;
			$lastFormsUpdate = \XF::options()->snogFormsLastUpdate;

			if ($sessionFormsCounts === null || ($sessionFormsCounts && ($sessionFormsCounts['lastBuilt'] < $lastFormsUpdate)))
			{
				/** @var \Snog\Forms\Repository\Form|\XF\Mvc\Entity\Repository $formRepo */
				$formRepo = \XF::repository('Snog\Forms:Form');

				/** @var \XF\Mvc\Entity\ArrayCollection|\Snog\Forms\Entity\Form[] $formValues */
				$formValues = $formRepo->findActiveFormsForList()->fetch()->filterViewable();

				$formsCounts = [
					'total' => $formValues->count(),
					'lastBuilt' => $lastFormsUpdate
				];

				$session->snogFormsCount = $formsCounts;
			}
		}
	}

}