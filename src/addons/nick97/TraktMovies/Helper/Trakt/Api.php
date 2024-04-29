<?php

namespace nick97\TraktMovies\Helper\Trakt;

class Api
{
	public function getClient()
	{
		$options = \XF::options();
		return new \nick97\TraktMovies\Trakt\ApiClient($options->traktthreads_apikey, $options->traktthreads_language);
	}
}