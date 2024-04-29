<?php

namespace Snog\TV\XF\Service\Thread;

class Creator extends XFCP_Creator
{
	protected function finalSetup()
	{
		parent::finalSetup();

		$typeHandler = $this->thread->TypeHandler;
		if ($typeHandler instanceof \Snog\TV\ThreadType\TV)
		{
			$typeHandler->onTvThreadCreate($this);
		}
	}
}