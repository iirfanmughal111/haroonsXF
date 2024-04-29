<?php


namespace nick97\TraktMovies\ForumType;


use XF\Entity\Forum;
use XF\Entity\Node;
use XF\Http\Request;
use XF\Mvc\Entity\Entity;
use XF\Mvc\FormAction;

class Movie extends \XF\ForumType\AbstractHandler
{
	/**
	 * @inheritDoc
	 */
	public function getDefaultThreadType(Forum $forum): string
	{
		return 'trakt_movies_movie';
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
		return 'fa-film';
	}

	public function getDefaultTypeConfig(): array
	{
		return [
			'available_genres' => ['All']
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
		/** @var \nick97\TraktMovies\Helper\Trakt\Api $apiHelper */
		$apiHelper = \XF::helper('nick97\TraktMovies:Trakt\Api');
		$traktClient = $apiHelper->getClient();

		$codes = $traktClient->getGenres()->getMovieList();
		if ($traktClient->hasError())
		{
			return null;
		}

		$availableGenres['All'] = 'All';

		foreach ($codes['genres'] as $genre)
		{
			$availableGenres[$genre['name']] = $genre['name'];
		}

		$reply->setParams(['availableGenres' => $availableGenres]);

		return 'trakt_movies_forum_type_config_movie';
	}

	public function getForumViewAndTemplate(Forum $forum): array
	{
		return ['nick97\TraktMovies:Forum\ViewTypeMovie', 'trakt_movies_forum_view_type_movie'];
	}

	public function getForumViewTemplateOverrides(Forum $forum, array $extra = []): array
	{
		return [
			'thread_list_macro' => 'trakt_movies_thread_list_movie_macros::item',
		];
	}

	public function getForumFilterInput(Forum $forum, \XF\Http\Request $request, array $filters): array
	{
		$input = $request->filter([
			'genre' => 'str',
			'cast' => 'str',
			'director' => 'str',
			'movie_title' => 'str',
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
		if ($input['movie_title'])
		{
			$filters['movie_title'] = $input['movie_title'];
		}

		return $filters;
	}

	public function applyForumFilters(Forum $forum, \XF\Finder\Thread $threadFinder, array $filters): \XF\Finder\Thread
	{
		if (!empty($filters['genre']))
		{
			$threadFinder->where('Movie.trakt_genres', 'LIKE', $threadFinder->escapeLike($filters['genre'], '%?%'));
		}
		if (!empty($filters['cast']))
		{
			$threadFinder->where('Movie.trakt_cast', 'LIKE', $threadFinder->escapeLike($filters['cast'], '%?%'));
		}
		if (!empty($filters['director']))
		{
			$threadFinder->where('Movie.trakt_director', 'LIKE', $threadFinder->escapeLike($filters['director'], '%?%'));
		}
		if (!empty($filters['movie_title']))
		{
			$threadFinder->where('Movie.trakt_title', 'LIKE', $threadFinder->escapeLike($filters['movie_title'], '%?%'));
		}

		return $threadFinder;
	}

	public function adjustForumFiltersPopup(Forum $forum, \XF\Mvc\Reply\View $filtersView): \XF\Mvc\Reply\AbstractReply
	{
		$filtersView->setTemplateName('trakt_movie_forum_filters_type_movie');
		return $filtersView;
	}

	public function adjustForumThreadListFinder(Forum $forum, \XF\Finder\Thread $threadFinder, int $page, \XF\Http\Request $request)
	{
		$threadFinder->with('Movie');
	}

	public function getThreadListSortOptions(Forum $forum, bool $forAdminConfig = false): array
	{
		$options = parent::getThreadListSortOptions($forum, $forAdminConfig);
		$options['trakt_movies_director'] = 'Movie.trakt_director';
		$options['trakt_movies_release'] = 'Movie.trakt_release';
		$options['trakt_movies_rating'] = 'Movie.trakt_rating';
		$options['trakt_movies_genres'] = 'Movie.trakt_genres';

		return $options;
	}

	public function getLdStructuredData(Forum $forum, $threads, int $page, array $extraData = [])
	{
		$data['@context'] = 'https://schema.org';
		$data['@type'] = 'ItemList';

		/** @var \nick97\TraktMovies\XF\Entity\Thread[] $threads */
		if ($threads)
		{
			$i = 1;
			foreach ($threads as $thread)
			{
				$movie = $thread->traktMovie;
				if (!$movie)
				{
					continue;
				}

				$data['itemListElement'][] = [
					'@type' => 'ListItem',
					'position' => $i,
					'item' => [
						'@type' => 'Movie',
						'url' => $thread->getContentUrl(),
						'name' => $movie->trakt_title,
						'image' => $movie->getImageUrl('l'),
						'dateCreated' => $movie->trakt_release,
						'director' => ['@type' => 'Person', 'name' => $movie->trakt_director],
					]
				];

				$i++;
			}
		}

		return $data;
	}

	public function isGenreAllowed(Forum $forum, $genres): bool
	{
		$typeConfig = $forum->getTypeConfig();
		if (empty($typeConfig['available_genres']))
		{
			return false;
		}

		if (isset($typeConfig['available_genres']['All']))
		{
			return true;
		}

		if (is_array($genres))
		{
			$allowed = false;
			foreach ($genres as $genre)
			{
				$allowed = $this->isGenreAllowed($forum, $genre);
				if ($allowed)
				{
					break;
				}
			}

			return $allowed;
		}

		return in_array(trim($genres), $typeConfig['available_genres']);
	}
}