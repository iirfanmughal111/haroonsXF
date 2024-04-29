<?php

namespace Snog\Movies\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Thread extends XFCP_Thread
{
	public function actionPreview(ParameterBag $params)
	{
		// HAVE TO QUERY FOR VIEWABLE TO GET THE NODE ID
		$thread = $this->assertViewableThread($params->thread_id, ['FirstPost', 'Movie']);
		if ($thread->discussion_type !== 'snog_movies_movie' || !isset($thread->Movie->tmdb_plot))
		{
			return parent::actionPreview($params);
		}

		$firstPost['user'] = $thread->user_id;
		$firstPost['message'] = $thread->Movie->tmdb_plot;
		$viewParams = ['thread' => $thread, 'firstPost' => $firstPost];
		return $this->view('XF:Thread\Preview', 'thread_preview', $viewParams);
	}

	protected function getThreadViewExtraWith()
	{
		$extraWith = parent::getThreadViewExtraWith();

		$extraWith[] = 'Movie';

		return $extraWith;
	}
}