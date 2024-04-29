<?php

namespace nick97\TraktIntegration\XF\Entity;

class Thread extends XFCP_Thread
{
	public function getTraktTVLink($id)
	{
		$recordExist = \XF::finder('nick97\TraktIntegration:TraktTVSlug')->where('tmdb_id', $id)->fetchOne();

		if ($recordExist) {
			return $recordExist["trakt_slug"];
		}

		return false;
	}

	public function getTraktMovLink($id)
	{
		$recordExist = \XF::finder('nick97\TraktIntegration:TraktMovSlug')->where('tmdb_id', $id)->fetchOne();

		if ($recordExist) {
			return $recordExist["trakt_slug"];
		}

		return false;
	}
}
