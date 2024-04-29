<?php
// FROM HASH: aafb1319fca4f497bbbc15f5d9bc0af7
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= ($__vars['alert']['extra_data']['room_name'] ? 'You have been unbanned from the room "' . (((('<a href="' . $__templater->func('link', array('chat/room', $__vars['alert']['extra_data'], ), true)) . '">') . $__templater->escape($__vars['alert']['extra_data']['room_name'])) . '</a>') . '".' : 'You have been unbanned from the chat.');
	return $__finalCompiled;
}
);