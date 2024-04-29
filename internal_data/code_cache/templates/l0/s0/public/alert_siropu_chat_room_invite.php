<?php
// FROM HASH: d50cb2f1f64a18cc8518a0afb5c595fa
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' has invited you to join the chat room "' . (((('<a href="' . $__templater->func('link', array('chat/room', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['room_name'])) . '</a>') . '".';
	return $__finalCompiled;
}
);