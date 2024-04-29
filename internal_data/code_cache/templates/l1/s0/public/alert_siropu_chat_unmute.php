<?php
// FROM HASH: 3346f93b04fabf1d27ee1f29fa202bbd
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= ($__vars['alert']['extra_data']['room_name'] ? 'You have been unmuted in the room "' . (((('<a href="' . $__templater->func('link', array('chat/room', $__vars['alert']['extra_data'], ), true)) . '">') . $__templater->escape($__vars['alert']['extra_data']['room_name'])) . '</a>') . '".' : 'You have been unmuted in the chat.');
	return $__finalCompiled;
}
);