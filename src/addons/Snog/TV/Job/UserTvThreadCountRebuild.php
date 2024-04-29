<?php

namespace Snog\TV\Job;

class UserTvThreadCountRebuild extends \XF\Job\AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT user_id
				FROM xf_user
				WHERE user_id > ?
				ORDER BY user_id
			", $batch
		), [$start]);
	}

	protected function rebuildById($id)
	{
		/** @var \Snog\TV\XF\Entity\User $user */
		$user = $this->app->em()->find('XF:User', $id);
		if ($user)
		{
			$user->rebuildTvThreadCount();
		}
	}

	protected function getStatusType()
	{
		return \XF::phrase('snog_tv_thread_count_rebuild');
	}
}