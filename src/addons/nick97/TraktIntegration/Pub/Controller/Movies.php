<?php

namespace nick97\TraktIntegration\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;

class Movies extends \Snog\Movies\Pub\Controller\Movies
{
	public function actionSync(ParameterBag $params)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id) {
			return $this->noPermission();
		}

		$thread = $this->assertViewableThread($params->thread_id);

		if ($this->isPost()) {

			$dummyId = time() - rand(1000, 9999);

			$finalId = $dummyId;

			$typeCreator = \XF::service('Snog\Movies:Thread\TypeData\MovieCreator', $thread, $finalId);

			$movieCreator = $typeCreator->getMovieCreator();

			if (isset($thread->Movie->tmdb_id)) {
				$movieId = $thread->Movie->tmdb_id;
			} else {
				return $this->redirect($this->buildLink('threads', $thread));
			}

			$threadId = $thread->thread_id;

			\xf::db()->delete('xf_snog_movies_thread', 'thread_id = ?', $thread->thread_id);

			\xf::db()->delete('xf_snog_movies_crew', 'tmdb_id = ?', $movieId);
			\xf::db()->delete('xf_snog_movies_cast', 'tmdb_id = ?', $movieId);

			$casts = $this->finder('Snog\Movies:Cast')->where('tmdb_id', $movieId)->fetch();

			if (count($casts)) {

				$this->deleteMovies($casts);
			}

			$Crews = $this->finder('Snog\Movies:Crew')->where('tmdb_id', $movieId)->fetch();

			if (count($Crews)) {

				$this->deleteMovies($Crews);
			}
			$Videos = $this->finder('Snog\Movies:Video')->where('tmdb_id', $movieId)->fetch();

			if (count($Videos)) {

				$this->deleteMovies($Videos);
			}

			$Ratings = $this->finder('Snog\Movies:Rating')->where('thread_id', $thread->thread_id)->fetch();

			if (count($Ratings)) {

				$this->deleteMovies($Ratings);
			}

			// $movie = $thread->traktMovie;
			// $message = $movie->getPostMessage();

			// $comment = $thread->FirstPost->message;

			// if (!\xf::options()->traktthreads_force_comments) {
			// 	$message .= $comment;
			// }



			// $thread->setOption('movieOriginalMessage', $comment);

			$movieCreator->setMovieId($movieId);

			$thread->setOption('movieApiResponse', $movieCreator->getMovieApiResponse());

			$movieCreator->save();

			$movie = $this->finder('Snog\Movies:Movie')->where('thread_id', $finalId)->fetchOne();
			$movie->fastUpdate('thread_id', $threadId);
			$thread->fastUpdate('title', $thread->Movie->getExpectedThreadTitle());

			return $this->redirect($this->buildLink('threads', $thread));
		} else {
			$viewParams = [
				'thread' => $thread,
			];
			return $this->view('nick97\TraktIntegration:Movies\Sync', 'nick97_trakt_watch_list_sync_confirm', $viewParams);
		}
	}

	public function deleteMovies($datas)
	{
		foreach ($datas as $data) {

			$data->delete();
		}
	}
}
