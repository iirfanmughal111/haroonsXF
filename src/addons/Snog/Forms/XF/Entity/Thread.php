<?php

namespace Snog\Forms\XF\Entity;

use XF\Mvc\Entity\Structure;

/**
 * Class Thread
 * @package Snog\Forms\XF\Entity
 *
 * @property \Snog\Forms\Entity\Promotion $Promotions
 * @property \Snog\Forms\Entity\Form $Form
 */
class Thread extends XFCP_Thread
{
	public function getQrform()
	{
		$qrform = 0;

		if ($this->node_id)
		{
			$finder = $this->finder('Snog\Forms:Form');

			/** @var \Snog\Forms\Entity\Form $form */
			$form = $finder->where('qroption', 1)
				->where('qrforums', 'LIKE', $finder->escapeLike($this->node_id, '%?%'))
				->fetchOne();

			if ($form)
			{
				$user = \XF::visitor();
				$isMatched = $form->checkUserCriteriaMatch($user, false, false);
				if ($isMatched)
				{
					$qrform = $form;
				}
			}
		}

		return $qrform;
	}
}