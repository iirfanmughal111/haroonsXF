<?php

namespace Snog\TV\Cron;

class TvWatchProviderUpdate
{
	public static function runDaily()
	{
		\XF::app()->jobManager()->enqueueUnique(
			'snogTvWatchProvider',
			'Snog\TV:TvWatchProviderRebuild',
			[],
			false
		);

		return true;
	}
}