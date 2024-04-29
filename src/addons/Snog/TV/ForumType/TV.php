<?php

namespace Snog\TV\ForumType;

use XF\Entity\Forum;
use XF\Entity\Node;
use XF\Http\Request;
use XF\Mvc\Entity\Entity;
use XF\Mvc\FormAction;

class TV extends \XF\ForumType\AbstractHandler
{

	/**
	 * @inheritDoc
	 */
	public function getDefaultThreadType(Forum $forum): string
	{
		return 'snog_tv';
	}

	/**
	 * @inheritDoc
	 */
	public function getDisplayOrder(): int
	{
		return 23000;
	}

	/**
	 * @inheritDoc
	 */
	public function getTypeIconClass(): string
	{
		return 'fa-tv-retro';
	}

	public function getDefaultTypeConfig(): array
	{
		return [
			'available_genres' => []
		];
	}

	protected function getTypeConfigColumnDefinitions(): array
	{
		return [
			'available_genres' => [
				'type' => Entity::LIST_ARRAY,
			]
		];
	}

	public function setupTypeConfigSave(FormAction $form, Node $node, Forum $forum, Request $request)
	{
		$validator = $this->getTypeConfigValidator($forum);

		$validator->bulkSet([
			'available_genres' => $request->filter('type_config.available_genres', 'array-str'),
		]);

		return $validator;
	}

	public function setupTypeConfigEdit(\XF\Mvc\Reply\View $reply, Node $node, Forum $forum, array &$typeConfig)
	{
		/** @var \Snog\TV\Helper\Tmdb\Api $apiHelper */
		$apiHelper = \XF::helper('Snog\TV:Tmdb\Api');
		$tmdbClient = $apiHelper->getClient();

		$codes = $tmdbClient->getGenres()->getTvList();
		if ($tmdbClient->hasError())
		{
			return null;
		}

		$availableGenres['All'] = 'All';

		foreach ($codes['genres'] as $genre)
		{
			$availableGenres[$genre['name']] = $genre['name'];
		}

		$reply->setParams(['availableGenres' => $availableGenres]);

		return 'snog_tv_forum_type_config_tv';
	}

	public function getForumViewAndTemplate(Forum $forum): array
	{
		return ['Snog\TV:Forum\ViewTypeTv', 'snog_tv_forum_view_type_tv'];
	}

	public function getForumViewTemplateOverrides(Forum $forum, array $extra = []): array
	{
		return [
			'thread_list_macro' => 'snog_tv_thread_list_tv_macros::item'
		];
	}

	public function getForumFilterInput(Forum $forum, \XF\Http\Request $request, array $filters): array
	{
		$input = $request->filter([
			'genre' => 'str',
			'cast' => 'str',
			'director' => 'str',
			'tv_title' => 'str',
			'tv_status' => 'str'
		]);

		if ($input['genre'])
		{
			$filters['genre'] = $input['genre'];
		}
		if ($input['cast'])
		{
			$filters['cast'] = $input['cast'];
		}
		if ($input['director'])
		{
			$filters['director'] = $input['director'];
		}
		if ($input['tv_title'])
		{
			$filters['tv_title'] = $input['tv_title'];
		}
		if ($input['tv_status'])
		{
			$filters['tv_status'] = $input['tv_status'];
		}

		return $filters;
	}

	public function applyForumFilters(Forum $forum, \XF\Finder\Thread $threadFinder, array $filters): \XF\Finder\Thread
	{
		if (!empty($filters['genre']))
		{
			$threadFinder->where('TV.tv_genres', 'LIKE', $threadFinder->escapeLike($filters['genre'], '%?%'));
		}
		if (!empty($filters['cast']))
		{
			$threadFinder->where('TV.tv_cast', 'LIKE', $threadFinder->escapeLike($filters['cast'], '%?%'));
		}
		if (!empty($filters['director']))
		{
			$threadFinder->where('TV.tv_director', 'LIKE', $threadFinder->escapeLike($filters['director'], '%?%'));
		}
		if (!empty($filters['tv_title']))
		{
			$threadFinder->where('TV.tv_title', 'LIKE', $threadFinder->escapeLike($filters['tv_title'], '%?%'));
		}
		if (!empty($filters['tv_status']))
		{
			$threadFinder->where('TV.status', '=', $filters['tv_status']);
		}

		return $threadFinder;
	}

	public function adjustForumFiltersPopup(Forum $forum, \XF\Mvc\Reply\View $filtersView): \XF\Mvc\Reply\AbstractReply
	{
		$filtersView->setTemplateName('snog_tv_forum_filters_type_tv');
		return $filtersView;
	}

	public function adjustForumThreadListFinder(Forum $forum, \XF\Finder\Thread $threadFinder, int $page, \XF\Http\Request $request)
	{
		$threadFinder->with('TV');
	}

	public function getThreadListSortOptions(Forum $forum, bool $forAdminConfig = false): array
	{
		$options = parent::getThreadListSortOptions($forum, $forAdminConfig);
		$options['snog_tv_creator'] = 'TV.tv_director';
		$options['snog_tv_first_aired'] = 'TV.tv_release';
		$options['snog_tv_rating'] = 'TV.tv_rating';
		$options['snog_tv_genres'] = 'TV.tv_genres';
		$options['snog_tv_season'] = 'TV.tv_season';
		$options['snog_tv_episode'] = 'TV.tv_episode';

		return $options;
	}
}