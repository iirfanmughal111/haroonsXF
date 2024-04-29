<?php
// FROM HASH: 274dae987ac871f6e771309c7f419b1d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'You have unread <a href="' . $__templater->func('link', array('chat/conversation/view', $__vars['content'], ), true) . '">chat conversation message(s)</a> from ' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . '.';
	return $__finalCompiled;
}
);