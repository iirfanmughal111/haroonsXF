<?php

namespace nick97\TraktMovies\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Thread extends XFCP_Thread
{
	public function actionPreview(ParameterBag $params)
	{
		// HAVE TO QUERY FOR VIEWABLE TO GET THE NODE ID
		$thread = $this->assertViewableThread($params->thread_id, ['FirstPost', 'traktMovie']);
		if ($thread->discussion_type !== 'trakt_movies_movie' || !isset($thread->traktMovie->trakt_plot)) {
			return parent::actionPreview($params);
		}

		$firstPost['user'] = $thread->user_id;
		$firstPost['message'] = $thread->traktMovie->trakt_plot;
		$viewParams = ['thread' => $thread, 'firstPost' => $firstPost];
		return $this->view('XF:Thread\Preview', 'thread_preview', $viewParams);
	}

	protected function getThreadViewExtraWith()
	{
		$extraWith = parent::getThreadViewExtraWith();

		$extraWith[] = 'traktMovie';

		return $extraWith;
	}
}
