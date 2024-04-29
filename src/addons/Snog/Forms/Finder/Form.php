<?php

namespace Snog\Forms\Finder;

class Form extends \XF\Mvc\Entity\Finder
{
	public function onlyActive()
	{
		$this->where('active', 1);
		return $this;
	}

	public function applyTypeDefaultOrder()
	{
		$this->with('Type');

		$this->setDefaultOrder([
			['Type.display', 'ASC'],
			['display', 'ASC'],
		]);

		return $this;
	}
}