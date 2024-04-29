<?php

namespace Snog\TV\Cron;

class TvChanges
{
	public static function run()
	{
		$app = \XF::app();
		$options = \XF::options();

		if ($options->TvThreads_trackChanges)
		{
			$app->jobManager()->enqueueUnique(
				'snogTvThreadChanges',
				'Snog\TV:TvThreadChanges',
				[],
				false
			);
		}

		if ($options->TvThreads_trackChangesAiringToday)
		{
			$app->jobManager()->enqueueUnique(
				'snogTvThreadChangesAiringToday',
				'Snog\TV:TvThreadChangesAiringToday',
				[],
				false
			);
		}

		if ($options->TvThreads_trackCommunityChanges)
		{
			$app->jobManager()->enqueueUnique(
				'snogTvCommunityChanges',
				'Snog\TV:TvCommunityChangesApply',
				[],
				false
			);
		}
	}
}
