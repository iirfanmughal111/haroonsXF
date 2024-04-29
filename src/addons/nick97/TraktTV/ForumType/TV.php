<?php

namespace nick97\TraktTV\ForumType;

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
		return 'trakt_tv';
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
		/** @var \nick97\TraktTV\Helper\Trakt\Api $apiHelper */
		$apiHelper = \XF::helper('nick97\TraktTV:Trakt\Api');
		$traktClient = $apiHelper->getClient();

		$codes = $traktClient->getGenres()->getTvList();
		if ($traktClient->hasError()) {
			return null;
		}

		$availableGenres['All'] = 'All';

		foreach ($codes['genres'] as $genre) {
			$availableGenres[$genre['name']] = $genre['name'];
		}

		$reply->setParams(['availableGenres' => $availableGenres]);

		return 'trakt_tv_forum_type_config_tv';
	}

	public function getForumViewAndTemplate(Forum $forum): array
	{
		return ['nick97\TraktTV:Forum\ViewTypeTv', 'trakt_tv_forum_view_type_tv'];
	}

	public function getForumViewTemplateOverrides(Forum $forum, array $extra = []): array
	{
		return [
			'thread_list_macro' => 'trakt_tv_thread_list_tv_macros::item'
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

		if ($input['genre']) {
			$filters['genre'] = $input['genre'];
		}
		if ($input['cast']) {
			$filters['cast'] = $input['cast'];
		}
		if ($input['director']) {
			$filters['director'] = $input['director'];
		}
		if ($input['tv_title']) {
			$filters['tv_title'] = $input['tv_title'];
		}
		if ($input['tv_status']) {
			$filters['tv_status'] = $input['tv_status'];
		}

		return $filters;
	}

	public function applyForumFilters(Forum $forum, \XF\Finder\Thread $threadFinder, array $filters): \XF\Finder\Thread
	{
		if (!empty($filters['genre'])) {
			$threadFinder->where('TV.tv_genres', 'LIKE', $threadFinder->escapeLike($filters['genre'], '%?%'));
		}
		if (!empty($filters['cast'])) {
			$threadFinder->where('TV.tv_cast', 'LIKE', $threadFinder->escapeLike($filters['cast'], '%?%'));
		}
		if (!empty($filters['director'])) {
			$threadFinder->where('TV.tv_director', 'LIKE', $threadFinder->escapeLike($filters['director'], '%?%'));
		}
		if (!empty($filters['tv_title'])) {
			$threadFinder->where('TV.tv_title', 'LIKE', $threadFinder->escapeLike($filters['tv_title'], '%?%'));
		}
		if (!empty($filters['tv_status'])) {
			$threadFinder->where('TV.status', '=', $filters['tv_status']);
		}

		return $threadFinder;
	}

	public function adjustForumFiltersPopup(Forum $forum, \XF\Mvc\Reply\View $filtersView): \XF\Mvc\Reply\AbstractReply
	{
		$filtersView->setTemplateName('trakt_tv_forum_filters_type_tv');
		return $filtersView;
	}

	public function adjustForumThreadListFinder(Forum $forum, \XF\Finder\Thread $threadFinder, int $page, \XF\Http\Request $request)
	{
		$threadFinder->with('TV');
	}

	public function getThreadListSortOptions(Forum $forum, bool $forAdminConfig = false): array
	{
		$options = parent::getThreadListSortOptions($forum, $forAdminConfig);
		$options['trakt_tv_creator'] = 'TV.tv_director';
		$options['trakt_tv_first_aired'] = 'TV.tv_release';
		$options['trakt_tv_rating'] = 'TV.tv_rating';
		$options['trakt_tv_genres'] = 'TV.tv_genres';
		$options['trakt_tv_season'] = 'TV.tv_season';
		$options['trakt_tv_episode'] = 'TV.tv_episode';

		return $options;
	}
}
