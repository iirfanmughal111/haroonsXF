<?php

namespace Snog\Movies\XF\Service\Thread;

class Creator extends XFCP_Creator
{
	protected function finalSetup()
	{
		parent::finalSetup();

		$typeHandler = $this->thread->TypeHandler;
		if ($typeHandler instanceof \Snog\Movies\ThreadType\Movie)
		{
			$typeHandler->onMovieThreadCreate($this);
		}
	}
}