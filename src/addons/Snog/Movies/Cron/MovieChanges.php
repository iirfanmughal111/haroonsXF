<?php

namespace Snog\Movies\Cron;

class MovieChanges
{
	public static function run()
	{
		$app = \XF::app();
		$options = \XF::options();

		if ($options->tmdbthreads_trackChanges)
		{
			$app->jobManager()->enqueueUnique(
				'snogMoviesChanges',
				'Snog\Movies:MovieChanges',
				[],
				false
			);
		}

		if ($options->tmdbthreads_trackCommunityChanges)
		{
			$app->jobManager()->enqueueUnique(
				'snogMoviesCommChanges',
				'Snog\Movies:MovieCommunityChangesApply',
				[],
				false
			);
		}
	}
}