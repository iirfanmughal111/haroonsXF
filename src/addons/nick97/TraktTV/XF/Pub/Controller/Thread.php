<?php

namespace nick97\TraktTV\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Thread extends XFCP_Thread
{
	protected function setupThreadReply(\XF\Entity\Thread $thread)
	{
		/** @var \nick97\TraktTV\XF\Service\Thread\Replier $replier */
		$replier = parent::setupThreadReply($thread);

		if ($thread->discussion_type == 'trakt_tv') {
			$season = $this->filter('season', 'uint');
			$episode = $this->filter('episode', 'uint');

			if ($season && $episode) {
				$replier->setTvSeasonEpisode($season, $episode);
			}
		}

		return $replier;
	}

	public function actionPreview(ParameterBag $params)
	{
		// HAVE TO QUERY FOR VIEWABLE TO GET THE NODE ID

		/** @var \nick97\TraktTV\XF\Entity\Thread $thread */
		$thread = $this->assertViewableThread($params->thread_id, ['FirstPost']);

		if (($thread->discussion_type != 'trakt_tv' && !isset($thread->traktTV->tv_plot)) || !isset($thread->traktTV->tv_plot)) {
			return parent::actionPreview($params);
		}

		$firstPost['user'] = $thread->user_id;
		$firstPost['message'] = $thread->traktTV->tv_plot;
		$viewParams = ['thread' => $thread, 'firstPost' => $firstPost];
		return $this->view('XF:Thread\Preview', 'thread_preview', $viewParams);
	}

	protected function getThreadViewExtraWith()
	{
		$extraWith = parent::getThreadViewExtraWith();

		$extraWith[] = 'traktTV';

		return $extraWith;
	}
}
