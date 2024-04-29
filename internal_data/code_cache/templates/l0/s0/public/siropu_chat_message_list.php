<?php
// FROM HASH: 52816bd1489a60d25929e926c8637c65
return array(
'macros' => array('room' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'messages' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__templater->isTraversable($__vars['messages'])) {
		foreach ($__vars['messages'] AS $__vars['message']) {
			if ($__templater->method($__vars['message'], 'isPastJoinTime', array())) {
				$__finalCompiled .= '
		' . $__templater->includeTemplate('siropu_chat_room_message_row', $__vars) . '
	';
			}
		}
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'conversation' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'messages' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__templater->isTraversable($__vars['messages'])) {
		foreach ($__vars['messages'] AS $__vars['message']) {
			$__finalCompiled .= '
		' . $__templater->includeTemplate('siropu_chat_conversation_message_row', $__vars) . '
	';
		}
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

';
	return $__finalCompiled;
}
);