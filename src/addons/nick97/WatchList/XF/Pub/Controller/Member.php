<?php

namespace nick97\WatchList\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Member extends XFCP_Member
{

	protected function userStats($userId)
	{
		$clientKey = \XF::options()->nick97_watch_list_trakt_api_key;

		if (!$clientKey) {
			throw $this->exception(
				$this->notFound(\XF::phrase("nick97_watch_list_trakt_api_key_not_found"))
			);
		}

		$app = \xf::app();
		$watchListService = $app->service('nick97\WatchList:watchList');

		$toArray = $watchListService->getStatsById($userId, $clientKey);

		$stats = [
			'moviesWatched' => isset($toArray["movies"]["watched"]) ? number_format($toArray["movies"]["watched"]) : null,
			'moviesTime' => $this->convertMinutes($toArray["movies"]["minutes"]),
			'episodesWatched' => isset($toArray["episodes"]["watched"]) ? number_format($toArray["episodes"]["watched"]) : null,
			'episodesTime' => $this->convertMinutes($toArray["episodes"]["minutes"]),
		];

		return $stats;
	}


	protected function convertMinutes($minutes)
	{
		// Convert minutes to hours
		$hours = floor($minutes / 60);
		// $remaining_minutes = $minutes % 60;

		// Convert hours to days
		$days = floor($hours / 24);
		// $remaining_hours = $hours % 24;

		// Convert days to months
		// Assuming 30 days per month (can be adjusted)
		$months = floor($days / 30);
		// $remaining_days = $days % 30;

		$viewParams = [
			'hours' => isset($hours) ? number_format($hours) : 0,
			'days' => isset($days) ? number_format($days) : 0,
			'months' => isset($months) ? number_format($months) : 0,
		];

		return $viewParams;
	}

	protected function checkStats($userId)
	{
		$user = $this->assertViewableUser($userId);

		$visitor = \XF::visitor();
		if (!$visitor->user_id) {
			return $this->noPermission();
		}

		if ($visitor->user_id == $user->user_id) {
			if (!\XF::visitor()->hasPermission('nick97_watch_list', 'view_own_stats')) {
				// throw $this->exception($this->noPermission());

				return [
					'stats' => [],
					'limit' => false
				];
			}
		} else {
			if (!\XF::visitor()->hasPermission('nick97_watch_list', 'view_anyone_stats')) {
				// throw $this->exception($this->noPermission());
				return [
					'stats' => [],
					'limit' => false
				];
			}
		}

		$finder = \XF::finder('XF:UserConnectedAccount');
		$traktUser = $finder
			->where('user_id', $userId)->where('provider', 'nick_trakt')
			->fetchOne();

		$traktUserId = null;
		$limit = false;

		if (!empty($traktUser)) {

			if (!$user->canViewStats($error)) {
				$traktUserId = null;
				$limit = true;
			} else {
				if (!empty($traktUser)) {
					$traktUserId = $traktUser['provider_key'];
				}
			}
		}

		$viewParams = [
			'stats' => $traktUserId ? $this->userStats($traktUserId) : '',
			'limit' => $limit
		];

		return $viewParams;
	}

	protected function checkWatchList($user_id)
	{
		$conditions = [
			['discussion_type', 'snog_movies_movie'],
			['discussion_type', 'nick97_trakt_movies'],
		];

		$tvConditions = [
			['discussion_type', 'nick97_trakt_tv'],
			['discussion_type', 'snog_tv'],
		];

		$threadIds = [];
		$tvThreadIds = [];

		$limit = false;


		$user = $this->assertViewableUser($user_id);

		$visitor = \XF::visitor();

		if (!$visitor->user_id) {
			return $this->noPermission();
		}

		if ($visitor->user_id == $user->user_id) {
			if (!\XF::visitor()->hasPermission('nick97_watch_list', 'view_own_watchlist')) {
				// throw $this->exception($this->noPermission());

				return [
					'movies' => [],
					'tvShows' => [],
					'limit' => false
				];
			}
		} else {
			if (!\XF::visitor()->hasPermission('nick97_watch_list', 'view_everyone_watchlist')) {
				// throw $this->exception($this->noPermission());

				return [
					'movies' => [],
					'tvShows' => [],
					'limit' => false
				];
			}
		}

		if ($user->canViewWatchList($error)) {
			$allThreadIds = $this->finder('nick97\WatchList:WatchList')
				->where('user_id', $user->user_id)->pluckfrom('thread_id')->fetch()->toArray();

			$threadIds = $this->finder('XF:Thread')
				->whereOr($conditions)->where('thread_id', $allThreadIds)->pluckfrom('thread_id')->fetch()->toArray();

			$tvThreadIds = $this->finder('XF:Thread')
				->whereOr($tvConditions)->where('thread_id', $allThreadIds)->pluckfrom('thread_id')->fetch()->toArray();
		} else {
			$limit = true;
		}

		if (count($threadIds) > 0) {
			$movies = $this->finder('Snog\Movies:Movie')->where('thread_id', $threadIds)->fetch()->toArray();
			// $traktMovies = $this->finder('nick97\TraktMovies:Movie')->where('thread_id', $threadIds)->fetch()->toArray();

			// $movies = array_merge($tmdbMovies, $traktMovies);
			// $movies = $traktMovies;
		} else {
			$movies = [];
		}

		if (count($tvThreadIds) > 0) {
			$tvShows = $this->finder('Snog\TV:TV')->where('thread_id', $tvThreadIds)->fetch()->toArray();
			// $traktTv = $this->finder('nick97\TraktTV:TV')->where('thread_id', $tvThreadIds)->fetch()->toArray();

			// $tvShows = array_merge($tmdbTv, $traktTv);
			// $tvShows = $traktTv;
		} else {
			$tvShows = [];
		}

		$watchList = [
			"movies" => $movies,
			"tvShows" => $tvShows,

			'limit' => $limit
		];

		return $watchList;
	}


	public function actionWatchlist(ParameterBag $params)
	{
		$watchList = $this->checkWatchList($params->user_id);
		$stats = $this->checkStats($params->user_id);

		$viewParams = [
			"movies" => $watchList['movies'],
			"tvShows" => $watchList['tvShows'],

			'limit' => $watchList['limit'],

			'stats' => $stats['stats'],
			'statsLimit' => $stats['limit']
		];

		return $this->view('XF:Member\Watchlist', 'nick97_watchlist_profile_watchlist', $viewParams);
	}
}
