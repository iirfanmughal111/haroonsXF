<?php

namespace nick97\TraktTV\Helper;

class TVPost
{
	protected function standardizeEpisodeData($input)
	{
		return array_replace([
			'post_id' => 0,
			'tv_id' => '',
			'tv_season' => 0,
			'tv_episode' => 0,
			'tv_aired' => '',
			'tv_image' => '',
			'tv_title' => '',
			'tv_plot' => '',
			'tv_cast' => '',
			'tv_guest' => '',
			'message' => '',
			'tv_checked' => 0,
			'tv_poster' => 0,
		], $input);
	}

	public function getThreadTitle(\XF\Entity\Thread $thread, array $episodeData, \nick97\TraktTV\Entity\TVForum $parentShow = null)
	{
		$episodeData = $this->standardizeEpisodeData($episodeData);

		if ($parentShow && !\XF::options()->traktTvThreads_episode_exclude) {
			$title = $parentShow->tv_title . ': ';
			$title .= 'S' . str_pad($episodeData['tv_season'], 2, '0', STR_PAD_LEFT);
			$title .= 'E' . str_pad($episodeData['tv_episode'], 2, '0', STR_PAD_LEFT);
			$title .= " " . $episodeData['tv_title'];

			return $title;
		}

		return $thread->title;
	}
}
