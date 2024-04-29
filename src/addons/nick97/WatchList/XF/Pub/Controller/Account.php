<?php

namespace nick97\WatchList\XF\Pub\Controller;

use \XF\Mvc\Reply\View;
use \XF\Mvc\FormAction;
use \XF\Entity\User;

class Account extends XFCP_Account
{
	/**
	 * @param User $visitor
	 * @return FormAction
	 */
	protected function savePrivacyProcess(User $visitor)
	{
		$form = parent::savePrivacyProcess($visitor);

		$visitor = \XF::visitor();

		if ($form instanceof FormAction) {
			$input = $this->filter([
				'privacy' => [
					'allow_view_watchlist' => 'str',
					'allow_view_stats' => 'str',
				]
			]);

			$userPrivacy = $visitor->getRelationOrDefault('Privacy');
			$form->setupEntityInput($userPrivacy, $input['privacy']);

			$form->complete(function () use ($visitor) {
				/** @var \XF\Repository\IP $ipRepo */
				$ipRepo = $this->repository('XF:Ip');
				$ipRepo->logIp($visitor->user_id, $this->request->getIp(), 'user', $visitor->user_id, 'privacy_edit');
			});
		}

		return $form;
	}


	public function actionWatchlist()
	{
		$visitor = \XF::visitor();

		if (!$visitor->user_id) {
			return $this->noPermission();
		}

		$conditions = [
			['discussion_type', 'snog_movies_movie'],
			['discussion_type', 'nick97_trakt_movies'],
		];

		$allThreadIds = $this->finder('nick97\WatchList:WatchList')
			->where('user_id', $visitor->user_id)->pluckfrom('thread_id')->fetch()->toArray();

		$threadIds = $this->finder('XF:Thread')
			->whereOr($conditions)->where('thread_id', $allThreadIds)->pluckfrom('thread_id')->fetch()->toArray();

		if (count($threadIds) > 0) {
			$tmdbMovies = $this->finder('Snog\Movies:Movie')->where('thread_id', $threadIds)->fetch()->toArray();
		} else {
			$tmdbMovies = null;
		}

		// $traktThreadIds = $this->finder('XF:Thread')
		// 	// ->where('discussion_type', 'trakt_movies_movie')->where('thread_id', $allThreadIds)->pluckfrom('thread_id')->fetch()->toArray();
		// 	->where('discussion_type', 'nick97_trakt_movies')->where('thread_id', $allThreadIds)->pluckfrom('thread_id')->fetch()->toArray();

		// if (count($traktThreadIds) > 0) {
		// 	$traktMovies = $this->finder('nick97\TraktMovies:Movie')->where('thread_id', $traktThreadIds)->fetch()->toArray();
		// } else {
		// 	$traktMovies = null;
		// }

		$tvConditions = [
			['discussion_type', 'snog_tv'],
			['discussion_type', 'nick97_trakt_tv'],
		];

		$tvThreadIds = $this->finder('XF:Thread')
			->whereOr($tvConditions)->where('thread_id', $allThreadIds)->pluckfrom('thread_id')->fetch()->toArray();

		if (count($tvThreadIds) > 0) {
			$tmdbTvShows = $this->finder('Snog\TV:TV')->where('thread_id', $tvThreadIds)->fetch()->toArray();
		} else {
			$tmdbTvShows = null;
		}

		// $traktTvThreadIds = $this->finder('XF:Thread')
		// 	->where('discussion_type', 'trakt_tv')->where('thread_id', $allThreadIds)->pluckfrom('thread_id')->fetch()->toArray();

		// if (count($traktTvThreadIds) > 0) {
		// 	$traktTvShows = $this->finder('nick97\TraktTV:TV')->where('thread_id', $traktTvThreadIds)->fetch()->toArray();
		// } else {
		// 	$traktTvShows = null;
		// }

		$viewpParams = [
			'pageSelected' => 'my',

			"movies" => $tmdbMovies,
			// "traktMovies" => $traktMovies,
			"tmdbTvShows" => $tmdbTvShows,
			// "traktTvShows" => $traktTvShows,
		];

		return $this->view('nick97\WatchList:index', 'nick97_watch_list_my_watch_list', $viewpParams);
	}
}
