<?php

namespace nick97\TraktTV\Repository;

class Network extends \XF\Mvc\Entity\Repository
{
	public function findNetworksForList()
	{
		return $this->finder('nick97\TraktTV:Network')
			->setDefaultOrder('network_id', 'ASC');
	}
}
