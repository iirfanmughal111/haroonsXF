<?php
// FROM HASH: 3f283b0770d4ffcb134358e2971563c7
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= ($__vars['alert']['extra_data']['room_name'] ? 'You have been unkicked from the room "' . (((('<a href="' . $__templater->func('link', array('chat/room', $__vars['alert']['extra_data'], ), true)) . '">') . $__templater->escape($__vars['alert']['extra_data']['room_name'])) . '</a>') . '".' : 'You have been unkicked from the chat.');
	return $__finalCompiled;
}
);