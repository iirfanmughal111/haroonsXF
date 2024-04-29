<?php

namespace Snog\Movies\Helper\Tmdb;

class Api
{
	public function getClient()
	{
		$options = \XF::options();
		return new \Snog\Movies\Tmdb\ApiClient($options->tmdbthreads_apikey, $options->tmdbthreads_language);
	}
}