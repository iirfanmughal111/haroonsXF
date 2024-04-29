<?php
// FROM HASH: 9276f74c8f49b09f1ebf43d8742b6aa5
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'You have joined the private room "' . (((('<a href="' . $__templater->func('link', array('chat/room', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['room_name'])) . '</a>') . '" created by "' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . '".';
	return $__finalCompiled;
}
);