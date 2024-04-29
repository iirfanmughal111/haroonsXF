<?php
// FROM HASH: 3ff60e3cef5d6784936ff1e25fb1c54b
return array(
'macros' => array('user_status' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'user' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__vars['user']['siropu_chat_status'] AND (!$__templater->method($__vars['xf']['visitor'], 'getSiropuChatSetting', array('hide_status', )))) {
		$__finalCompiled .= '
		<div class="siropuChatUserStatus">' . $__templater->escape($__vars['user']['siropu_chat_status']) . '</div>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'activity_status' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'user' => '!',
		'roomId' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<span class="siropuChatActivityStatus" data-status="' . ($__vars['roomId'] ? $__templater->escape($__templater->method($__vars['user'], 'getSiropuChatActivityStatus', array(($__vars['user']['user_id'] ? $__vars['roomId'] : null), ))) : (($__vars['user'] AND $__templater->method($__vars['user'], 'isOnline', array())) ? 'active' : 'idle')) . '" title="' . $__templater->filter('Last activity', array(array('for_attr', array()),), true) . ': ' . ($__vars['roomId'] ? $__templater->func('date_time', array(($__vars['user']['user_id'] ? $__vars['user']['siropu_chat_rooms'][$__vars['roomId']] : $__vars['user']->{'siropu_chat_last_activity'}), ), true) : (($__vars['user'] AND $__templater->method($__vars['user'], 'canViewOnlineStatus', array())) ? $__templater->func('date_time', array($__vars['user']['last_activity'], ), true) : 'N/A')) . '"></span>
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