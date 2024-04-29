<?php

namespace OzzModz\DisplayVisitorName\Helper;

class Replacer
{
	public function replaceVisitorName($string, $context = '')
	{
		$visitor = \XF::visitor();
		$username = $visitor->user_id ? $visitor->username : \XF::phrase('ozzmodz_dvn_guest');

		if ($visitor->user_id && $context == 'message')
		{
			$username = \XF::app()->stringFormatter()->convertStructuredTextMentionsToBbCode(
				"[USER={$visitor->user_id}]{$username}[/USER]"
			);
		}

		return str_ireplace(\XF::options()->ozzmodz_dvn_token, $username, $string);
	}
}