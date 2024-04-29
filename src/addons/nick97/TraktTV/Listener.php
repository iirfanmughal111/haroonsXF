<?php

namespace nick97\TraktTV;

use XF\Mvc\Entity\Entity;
use XF\PrintableException;

/**
 * Class Listener
 *
 * @package nick97\TraktTV
 */
class Listener
{

	/**
	 * Allows direct modification of the Entity structure.
	 *
	 * Event hint: Fully qualified name of the root class that was called.
	 *
	 * @param \XF\Mvc\Entity\Manager $em Entity Manager object.
	 * @param \XF\Mvc\Entity\Structure $structure Entity Structure object.
	 */
	public static function forumEntityStructure(\XF\Mvc\Entity\Manager $em, \XF\Mvc\Entity\Structure &$structure)
	{
		$structure->relations += [
			'TVForum' => [
				'entity' => 'nick97\TraktTV:TVForum',
				'type' => Entity::TO_ONE,
				'conditions' => 'node_id',
				'primary' => true
			],
		];

		$structure->defaultWith[] = 'TVForum';
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
		$structure->relations['TVForum'] = [
			'entity' => 'nick97\TraktTV:TVForum',
			'type' => Entity::TO_ONE,
			'conditions' => 'node_id',
		];

		$structure->defaultWith[] = 'TVForum';
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
		$structure->relations['TVPost'] = [
			'entity' => 'nick97\TraktTV:TVPost',
			'type' => Entity::TO_ONE,
			'conditions' => 'post_id',
			'primary' => true
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
	public static function threadEntityStructure(\XF\Mvc\Entity\Manager $em, \XF\Mvc\Entity\Structure &$structure)
	{
		$structure->relations['traktTV'] = [
			'entity' => 'nick97\TraktTV:TV',
			'type' => Entity::TO_ONE,
			'conditions' => 'thread_id',
			'primary' => true
		];

		$structure->defaultWith[] = 'traktTV';
		$structure->options['tvData'] = [];
		$structure->options['tvOriginalMessage'] = '';
	}

	/**
	 * Event fires after the Entity specific _postDelete() method is called and after the post delete
	 * of any behaviors.
	 *
	 * Event hint: Fully qualified name of the root class that was called.
	 *
	 * @param Entity $entity Entity object.
	 * @throws PrintableException
	 */
	public static function nodeEntityPostDelete(Entity $entity)
	{
		/** @var \XF\Entity\Node $entity */
		/** @var \nick97\TraktTV\Entity\TVForum $entity */

		$tvNode = $entity->finder('nick97\TraktTV:TVForum')->where('node_id', $entity->node_id)->fetchOne();
		if ($tvNode) {
			$tvNode->delete();
		}
	}


	/**
	 * Event fires after the Entity specific _postDelete() method is called and after the post delete
	 * of any behaviors.
	 *
	 * Event hint: Fully qualified name of the root class that was called.
	 *
	 * @param Entity $entity Entity object.
	 */
	public static function forumEntityPostDelete(Entity $entity)
	{
		/** @var \XF\Entity\Forum $entity */
		$entity->db()->delete('nick97_trakt_tv_forum', 'node_id = ?', $entity->node_id);
	}


	/**
	 * Event fires after the Entity specific _postDelete() method is called and after the post delete
	 * of any behaviors.
	 *
	 * Event hint: Fully qualified name of the root class that was called.
	 *
	 * @param Entity $entity Entity object.
	 */
	public static function threadEntityPostDelete(Entity $entity)
	{
		/** @var \nick97\TraktTV\XF\Entity\Thread $entity */
		if ($entity->traktTV) {
			$entity->traktTV->delete();
		}
	}

	/**
	 * Event fires after the Entity specific _postDelete() method is called and after the post delete
	 * of any behaviors.
	 *
	 * Event hint: Fully qualified name of the root class that was called.
	 *
	 * @param Entity $entity Entity object.
	 */
	public static function postEntityPostDelete(Entity $entity)
	{
		/** @var \nick97\TraktTV\XF\Entity\Post $entity */
		$tvPost = $entity->TVPost;
		if ($tvPost) {
			$tvPost->delete();
		}
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
		$structure->columns['trakt_tv_thread_count'] = ['type' => Entity::UINT, 'default' => 0];
	}

	/**
	 * Event fires after the Entity specific _postSave() method is called and after the post save of
	 * any behaviors.
	 *
	 * Event hint: Fully qualified name of the root class that was called.
	 *
	 * @param Entity $entity Entity object.
	 */
	public static function optionEntityPostSave(Entity $entity)
	{
	}


	/**
	 * Called while testing a user against user criteria in \XF\Criteria\User::isMatch() for trophies,
	 * notices etc.
	 *
	 * @param string $rule Text identifying the criteria that should be
	 *                                     checked.
	 * @param array $data Data defining the conditions of the criteria.
	 * @param \XF\Entity\User $user User entity object to be used in the criteria
	 *                                     checks.
	 * @param bool $returnValue The event code should set this to true if a criteria
	 *                                     check matches.
	 */
	public static function criteriaUser(string $rule, array $data, \XF\Entity\User $user, bool &$returnValue)
	{
		if ($rule == 'tv_posted') {
			/** @var \nick97\TraktTV\XF\Entity\User $user */
			$returnValue = $user->trakt_tv_thread_count >= $data['tv'];;
		}
	}


	/**
	 * Allows direct modification of the Entity structure.
	 *
	 * Event hint: Fully qualified name of the root class that was called.
	 *
	 * @param \XF\Mvc\Entity\Manager $em Entity Manager object.
	 * @param \XF\Mvc\Entity\Structure $structure Entity Structure object.
	 */
	public static function userOptionEntityStructure(\XF\Mvc\Entity\Manager $em, \XF\Mvc\Entity\Structure &$structure)
	{
		$structure->columns['nick97_tv_trakt_watch_region'] = ['type' => Entity::STR, 'default' => 'US', 'maxLength' => 2];
	}


	/**
	 * Allows the modification of various properties for template macros before they are rendered.
	 *
	 * Event hint: A string representing the template type, template name and macro name, e.g. public:template_name:macro_name.
	 *
	 * @param \XF\Template\Templater $templater Templater object.
	 * @param mixed $type Template type.
	 * @param mixed $template Template name.
	 * @param mixed $name Macro name.
	 * @param array $arguments Array of arguments passed to this macro.
	 * @param array $globalVars Array of global vars available to this macro.
	 */
	public static function templaterMacroPreRender(\XF\Template\Templater $templater, &$type, &$template, &$name, array &$arguments, array &$globalVars)
	{
		if ($arguments['group'] instanceof \XF\Entity\OptionGroup && $arguments['group']->group_id == 'TvThreads') {
			$template = 'trakt_tv_option_macros';
			$name = 'option_form_block';

			$arguments['tabs'] = [
				'general' => [
					'label' => \XF::phrase('general_options'),
					'start' => 0,
					'end' => 1000,
					'active' => true
				],
				'appearance' => [
					'label' => \XF::phrase('option_group.appearance'),
					'start' => 1000,
					'end' => 3000,
					'active' => false
				],
				'performance' => [
					'label' => \XF::phrase('option_group.performance'),
					'start' => 3000,
					'end' => 5000,
					'active' => false
				],
			];
		}
	}
}
