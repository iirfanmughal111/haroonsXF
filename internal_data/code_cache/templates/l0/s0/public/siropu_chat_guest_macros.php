<?php
// FROM HASH: 1790bd4712cbc8364bd34332ea5ac58b
return array(
'macros' => array('username' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'user' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<span class="siropuChatGuest">(' . 'Guest' . ')</span>
	';
	if ($__templater->method($__vars['xf']['visitor'], 'canSanctionSiropuChat', array())) {
		$__finalCompiled .= '
		' . $__templater->func('username_link', array($__vars['user'], false, array(
			'defaultname' => 'Guest',
			'href' => $__templater->func('link', array('chat/guest', '', array('nickname' => $__vars['user']['username'], ), ), false),
			'data-xf-click' => 'overlay',
		))) . '
	';
	} else {
		$__finalCompiled .= '
		' . $__templater->func('username_link', array($__vars['user'], false, array(
			'defaultname' => 'Guest',
		))) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';

	return $__finalCompiled;
}
);