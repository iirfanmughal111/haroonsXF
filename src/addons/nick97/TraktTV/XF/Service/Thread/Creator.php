<?php

namespace nick97\TraktTV\XF\Service\Thread;

class Creator extends XFCP_Creator
{
	protected function finalSetup()
	{
		parent::finalSetup();

		$typeHandler = $this->thread->TypeHandler;
		if ($typeHandler instanceof \nick97\TraktTV\ThreadType\TV) {
			$typeHandler->onTvThreadCreate($this);
		}
	}
}
