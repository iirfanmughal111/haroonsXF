<?php

namespace nick97\WatchList\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;
use XF\Mvc\RouteMatch;

class WatchList extends AbstractController
{

	public function actionIndex(ParameterBag $params)
	{
		$conditions = [
			['discussion_type', 'snog_movies_movie'],
			['discussion_type', 'nick97_trakt_movies'],
		];

		$tvConditions = [
			['discussion_type', 'snog_tv'],
			['discussion_type', 'nick97_trakt_tv'],
		];

		$threadIds = [];
		$tvThreadIds = [];

		// get visitor
		$visitor = \XF::visitor();

		if (!$visitor->user_id) {
			return $this->noPermission();
		}

		// get permission
		if ($visitor->hasPermission('nick97_watch_list', 'view_own_watchlist')) {

			$allThreadIds = $this->finder('nick97\WatchList:WatchList')
				->where('user_id', $visitor->user_id)->pluckfrom('thread_id')->fetch()->toArray();

			$threadIds = $this->finder('XF:Thread')
				->whereOr($conditions)->where('thread_id', $allThreadIds)->pluckfrom('thread_id')->fetch()->toArray();

			$tvThreadIds = $this->finder('XF:Thread')
				->whereOr($tvConditions)->where('thread_id', $allThreadIds)->pluckfrom('thread_id')->fetch()->toArray();
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

		$providerFinder = \XF::finder('XF:UserConnectedAccount');
		$traktUser = $providerFinder
			->where('user_id', $visitor->user_id)->where('provider', 'nick_trakt')
			->fetchOne();

		$traktUserId = null;

		if (!empty($traktUser)) {
			$traktUserId = $traktUser['provider_key'];
		}

		$viewpParams = [
			'stats' => $traktUserId ? $this->userStats($traktUserId) : '',
			"movies" => $movies,
			"tvShows" => $tvShows,
		];

		return $this->view('nick97\WatchList:index', 'nick97_watch_list_index', $viewpParams);
	}

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
}
