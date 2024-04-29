<?php

namespace nick97\TraktIntegration\XF\Service\Thread;

class Creator extends XFCP_Creator
{
	protected function finalSetup()
	{
		parent::finalSetup();

		$typeHandler = $this->thread->TypeHandler;
		if ($typeHandler instanceof \nick97\TraktIntegration\ThreadType\TV) {

			$typeHandler->onTvThreadCreate($this);
		} else if ($typeHandler instanceof \nick97\TraktIntegration\ThreadType\Movie) {
			$typeHandler->onMovieThreadCreate($this);
		}
	}
}
