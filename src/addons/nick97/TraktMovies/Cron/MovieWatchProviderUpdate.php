<?php

namespace nick97\TraktMovies\Cron;

class MovieWatchProviderUpdate
{
	public static function runDaily()
	{
		\XF::app()->jobManager()->enqueueUnique(
			'traktMoviesWatchProvider',
			'nick97\TraktMovies:MovieWatchProvidersRebuild',
			[],
			false
		);

		return true;
	}
}