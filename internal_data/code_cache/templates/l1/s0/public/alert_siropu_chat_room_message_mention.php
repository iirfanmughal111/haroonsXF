<?php
// FROM HASH: 72bf1ee4f2047701789d90cc112cc8de
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' mentioned you in a <a href="' . $__templater->func('link', array('chat/message/view', $__vars['content'], ), true) . '">chat message</a> in "' . (((('<a href="' . $__templater->func('link', array('chat/room', $__vars['content']['Room'], ), true)) . '">') . $__templater->escape($__vars['content']['Room']['room_name'])) . '</a>') . '" room.';
	return $__finalCompiled;
}
);