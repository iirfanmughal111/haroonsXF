<?php

namespace Snog\TV\Repository;

class Network extends \XF\Mvc\Entity\Repository
{
	public function findNetworksForList()
	{
		return $this->finder('Snog\TV:Network')
			->setDefaultOrder('network_id', 'ASC');
	}
}