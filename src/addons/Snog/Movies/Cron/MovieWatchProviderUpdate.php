<?php

namespace Snog\Movies\Cron;

class MovieWatchProviderUpdate
{
	public static function runDaily()
	{
		\XF::app()->jobManager()->enqueueUnique(
			'snogMoviesWatchProvider',
			'Snog\Movies:MovieWatchProvidersRebuild',
			[],
			false
		);

		return true;
	}
}