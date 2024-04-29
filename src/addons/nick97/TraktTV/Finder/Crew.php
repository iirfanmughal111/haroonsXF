<?php

namespace nick97\TraktTV\Finder;

class Crew extends \XF\Mvc\Entity\Finder
{
	public function useDefaultOrder()
	{
		if ($this->app()->options()->traktTvThreads_aggregateCredits) {
			$this->order('total_episode_count', 'DESC');
		} else {
			$this->order('order');
		}

		return $this;
	}
}
