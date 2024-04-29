<?php

namespace nick97\TraktTV\Cron;

class TvChanges
{
	public static function run()
	{
		$app = \XF::app();
		$options = \XF::options();

		if ($options->traktTvThreads_trackChanges) {
			$app->jobManager()->enqueueUnique(
				'traktTvThreadChanges',
				'nick97\TraktTV:TvThreadChanges',
				[],
				false
			);
		}

		if ($options->traktTvThreads_trackChangesAiringToday) {
			$app->jobManager()->enqueueUnique(
				'traktTvThreadChangesAiringToday',
				'nick97\TraktTV:TvThreadChangesAiringToday',
				[],
				false
			);
		}

		if ($options->traktTvThreads_trackCommunityChanges) {
			$app->jobManager()->enqueueUnique(
				'traktTvCommunityChanges',
				'nick97\TraktTV:TvCommunityChangesApply',
				[],
				false
			);
		}
	}
}
