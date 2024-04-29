<?php
// FROM HASH: 30702eabdab21b7940d475eb3d74723f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' has started a <a href="' . $__templater->func('link', array('chat/conversation/view', $__vars['content'], ), true) . '">private chat conversation</a> with you.';
	return $__finalCompiled;
}
);