<?php

namespace nick97\WatchList\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Thread extends XFCP_Thread
{

	public function actionWatchList(ParameterBag $params)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id) {
			return $this->noPermission();
		}

		$thread = $this->assertViewableThread($params->thread_id);

		if ($this->isPost()) {
			if ($this->filter('stop', 'bool')) {
				$recordWatchList = $this->finder('nick97\WatchList:WatchList')->where('user_id', $visitor->user_id)->where('thread_id', $thread->thread_id)->fetchOne();

				$recordWatchList->delete();
			} else {
				$insertData = $this->em()->create('nick97\WatchList:WatchList');

				$insertData->user_id = $visitor->user_id;
				$insertData->thread_id = $thread->thread_id;
				$insertData->save();
			}

			$redirect = $this->redirect($this->buildLink('threads', $thread));
			$redirect->setJsonParam('switchKey', $this->filter('stop', 'bool') ? 'watch' : 'unwatch');
			return $redirect;
		} else {
			$viewParams = [
				'thread' => $thread,
			];
			return $this->view('XF:Thread\WatchList', 'fs_watch_list_thread_watch_list', $viewParams);
		}
	}


	public function actionMyWatchList(ParameterBag $params)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id) {
			return $this->noPermission();
		}

		$thread = $this->assertViewableThread($params->thread_id);

		if ($this->isPost()) {
			$recordWatchList = $this->finder('nick97\WatchList:WatchList')->where('user_id', $visitor->user_id)->where('thread_id', $thread->thread_id)->fetchOne();

			$recordWatchList->delete();

			$redirect = $this->redirect($this->buildLink('account/watchlist'));
			return $redirect;
		} else {
			$viewParams = [
				'thread' => $thread,
			];
			return $this->view('XF:Thread\WatchList', 'nick97_trakt_thread_my_watch_list', $viewParams);
		}
	}
}
