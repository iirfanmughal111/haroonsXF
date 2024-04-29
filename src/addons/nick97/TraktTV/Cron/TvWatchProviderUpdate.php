<?php

namespace nick97\TraktTV\Cron;

class TvWatchProviderUpdate
{
	public static function runDaily()
	{
		\XF::app()->jobManager()->enqueueUnique(
			'traktTvWatchProvider',
			'nick97\TraktTV:TvWatchProviderRebuild',
			[],
			false
		);

		return true;
	}
}
