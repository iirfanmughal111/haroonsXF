<?php

namespace nick97\TraktMovies\Cron;

class MovieChanges
{
	public static function run()
	{
		$app = \XF::app();
		$options = \XF::options();

		if ($options->traktthreads_trackChanges)
		{
			$app->jobManager()->enqueueUnique(
				'traktMoviesChanges',
				'nick97\TraktMovies:MovieChanges',
				[],
				false
			);
		}

		if ($options->traktthreads_trackCommunityChanges)
		{
			$app->jobManager()->enqueueUnique(
				'traktMoviesCommChanges',
				'nick97\TraktMovies:MovieCommunityChangesApply',
				[],
				false
			);
		}
	}
}