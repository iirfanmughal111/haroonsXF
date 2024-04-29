<?php
// FROM HASH: bde05c295197f222ff78eac489903c38
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['content']['sanction_end']) {
		$__finalCompiled .= '
	';
		if ($__vars['content']['sanction_reason']) {
			$__finalCompiled .= '
		';
			if ($__vars['content']['Room']) {
				$__finalCompiled .= '
			' . 'You have been muted in the room "' . (((('<a href="' . $__templater->func('link', array('chat/room', $__vars['content']['Room'], ), true)) . '">') . $__templater->escape($__vars['content']['Room']['room_name'])) . '</a>') . '" by ' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' until ' . $__templater->func('date_time', array($__vars['content']['sanction_end'], ), true) . ' for the following reason: ' . $__templater->escape($__vars['content']['sanction_reason']) . '.' . '
		';
			} else {
				$__finalCompiled .= '
			' . 'You have been muted in the chat by ' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' until ' . $__templater->func('date_time', array($__vars['content']['sanction_end'], ), true) . ' for the following reason: ' . $__templater->escape($__vars['content']['sanction_reason']) . '.' . '
		';
			}
			$__finalCompiled .= '
	';
		} else {
			$__finalCompiled .= '
		';
			if ($__vars['content']['Room']) {
				$__finalCompiled .= '
			' . 'You have been muted in the room "' . (((('<a href="' . $__templater->func('link', array('chat/room', $__vars['content']['Room'], ), true)) . '">') . $__templater->escape($__vars['content']['Room']['room_name'])) . '</a>') . '" by ' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' until ' . $__templater->func('date_time', array($__vars['content']['sanction_end'], ), true) . '.' . '
		';
			} else {
				$__finalCompiled .= '
			' . 'You have been muted in the chat by ' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' until ' . $__templater->func('date_time', array($__vars['content']['sanction_end'], ), true) . '.' . '
		';
			}
			$__finalCompiled .= '
	';
		}
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		if ($__vars['content']['sanction_reason']) {
			$__finalCompiled .= '
		';
			if ($__vars['content']['Room']) {
				$__finalCompiled .= '
			' . 'You have been muted permanently in the room "' . (((('<a href="' . $__templater->func('link', array('chat/room', $__vars['content']['Room'], ), true)) . '">') . $__templater->escape($__vars['content']['Room']['room_name'])) . '</a>') . '" by ' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' for the following reason: ' . $__templater->escape($__vars['content']['sanction_reason']) . '' . '
		';
			} else {
				$__finalCompiled .= '
			' . 'You have been muted permanently in the chat by ' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' for the following reason: ' . $__templater->escape($__vars['content']['sanction_reason']) . '' . '
		';
			}
			$__finalCompiled .= '
	';
		} else {
			$__finalCompiled .= '
		';
			if ($__vars['content']['Room']) {
				$__finalCompiled .= '
			' . 'You have been muted permanently in the room "' . (((('<a href="' . $__templater->func('link', array('chat/room', $__vars['content']['Room'], ), true)) . '">') . $__templater->escape($__vars['content']['Room']['room_name'])) . '</a>') . '" by ' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . '.' . '
		';
			} else {
				$__finalCompiled .= '
			' . 'You have been muted permanently in the chat by ' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . '.' . '
		';
			}
			$__finalCompiled .= '
	';
		}
		$__finalCompiled .= '
';
	}
	return $__finalCompiled;
}
);