<?php

namespace nick97\TraktMovies;

use XF\Mvc\Entity\Entity;

/**
 * Class Listener
 *
 * @package nick97\TraktMovies
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
		$structure->columns['default_sort_order'] = [
			'type' => Entity::STR, 'default' => 'last_post_date',
			'allowedValues' => ['title', 'post_date', 'reply_count', 'view_count', 'last_post_date', 'Movie.trakt_rating']
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
		$structure->relations['traktMovie'] = [
			'entity' => 'nick97\TraktMovies:Movie',
			'type' => Entity::TO_ONE,
			'conditions' => 'thread_id',
			'primary' => true
		];

		$structure->options['movieApiResponse'] = [];
		$structure->options['movieOriginalMessage'] = '';

		$structure->defaultWith[] = 'traktMovie';
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
		$structure->columns['trakt_movies_thread_count'] = ['type' => Entity::UINT, 'default' => 0];
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
		if ($rule == 'movies_posted') {
			/** @var \nick97\TraktMovies\XF\Entity\User $user */
			$returnValue = $user->trakt_movies_thread_count && $user->trakt_movies_thread_count >= $data['movies'];
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
		$structure->columns['nick97_movies_trakt_watch_region'] = ['type' => Entity::STR, 'default' => 'US', 'maxLength' => 2];
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
		if ($arguments['group'] instanceof \XF\Entity\OptionGroup && $arguments['group']->group_id == 'traktMovies') {
			$template = 'trakt_movies_option_macros';
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
