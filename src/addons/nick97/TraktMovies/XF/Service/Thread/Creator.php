<?php

namespace nick97\TraktMovies\XF\Service\Thread;

class Creator extends XFCP_Creator
{
	protected function finalSetup()
	{
		parent::finalSetup();

		$typeHandler = $this->thread->TypeHandler;
		if ($typeHandler instanceof \nick97\TraktMovies\ThreadType\Movie) {
			$typeHandler->onMovieThreadCreate($this);
		}
	}
}
