<?php

namespace DBTech\Credits\EventTrigger;

use DBTech\Credits\Entity\Transaction;
use XF\Mvc\Entity\Entity;

/**
 * Class Sticky
 *
 * @package DBTech\Credits\EventTrigger
 */
class Sticky extends AbstractHandler
{
	/**
	 *
	 */
	protected function setupOptions(): void
	{
		$this->options = array_replace($this->options, [
			'canRevert' => true,
			'canRebuild' => true,
		]);
	}
	
	/**
	 * @param Transaction $transaction
	 *
	 * @return mixed
	 */
	public function alertTemplate(Transaction $transaction): string
	{
		// For the benefit of the template
		$which = $transaction->amount < 0.00 ? 'spent' : 'earned';
		
		if ($transaction->negate)
		{
			if ($which == 'spent')
			{
				return $this->getAlertPhrase('dbtech_credits_lost_x_y_via_sticky_negate', $transaction);
			}
			else
			{
				return $this->getAlertPhrase('dbtech_credits_gained_x_y_via_sticky_negate', $transaction);
			}
		}
		else
		{
			if ($which == 'spent')
			{
				return $this->getAlertPhrase('dbtech_credits_lost_x_y_via_sticky', $transaction);
			}
			else
			{
				return $this->getAlertPhrase('dbtech_credits_gained_x_y_via_sticky', $transaction);
			}
		}
	}
	
	/**
	 * @return string|null
	 */
	public function getOptionsTemplate(): ?string
	{
		return null;
	}
	
	/**
	 * @param \XF\Mvc\Entity\Entity entity
	 *
	 * @throws \XF\PrintableException
	 */
	public function rebuild(Entity $entity): void
	{
		/** @var \DBTech\Credits\XF\Entity\Thread $entity */
		
		if ($entity->isVisible() && $entity->sticky)
		{
			$this->apply($entity->thread_id, [
				'node_id' => $entity->node_id,
				'multiplier' => $entity->FirstPost->message,
				
				'content_type' => 'thread',
				'content_id'   => $entity->thread_id,
				
				'timestamp'   => $entity->post_date,
				'enableAlert' => false,
				'runPostSave' => false
			], $entity->User);
		}
	}
	
	/**
	 * @param bool $forView
	 *
	 * @return array
	 */
	public function getEntityWith(bool $forView = false): array
	{
		return ['User', 'FirstPost'];
	}
}