<?php
// FROM HASH: ede056d6220da973fd33621b307a6ed0
return array(
'macros' => array('room' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'room' => '1',
		'users' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if (!$__templater->test($__vars['users'], 'empty', array())) {
		$__finalCompiled .= '
		';
		if ($__templater->isTraversable($__vars['users'])) {
			foreach ($__vars['users'] AS $__vars['user']) {
				$__finalCompiled .= '
			' . $__templater->callMacro(null, 'room_user', array(
					'room' => $__vars['room'],
					'user' => $__vars['user'],
				), $__vars) . '
		';
			}
		}
		$__finalCompiled .= '
	';
	} else {
		$__finalCompiled .= '
		<li class="siropuChatNoRoomUsers">' . 'No one is chatting at the moment.' . '</li>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'room_user' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'room' => '!',
		'user' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<li data-user-id="' . $__templater->escape($__vars['user']['user_id']) . '" data-username="' . $__templater->filter($__vars['user']['username'], array(array('for_attr', array()),), true) . '" data-last-active="' . $__templater->escape($__vars['user']['siropu_chat_last_activity']) . '">
		';
	if ($__vars['xf']['options']['siropuChatAvatarInUserList']) {
		$__finalCompiled .= '
			';
		if ($__vars['user']['user_id']) {
			$__finalCompiled .= '
				' . $__templater->func('avatar', array($__vars['user'], 'xxs', false, array(
				'defaultname' => 'Unknown',
				'itemprop' => 'image',
			))) . '
			';
		} else {
			$__finalCompiled .= '
				' . $__templater->func('avatar', array($__vars['user'], 'xxs', false, array(
				'defaultname' => 'Guest',
				'itemprop' => 'image',
				'href' => '',
			))) . '
			';
		}
		$__finalCompiled .= '
		';
	}
	$__finalCompiled .= '
		';
	if ($__vars['user']['user_id']) {
		$__finalCompiled .= '
			<a role="button" data-xf-click="menu" aria-expanded="false" aria-haspopup="true">
				' . $__templater->func('username_link', array($__vars['user'], true, array(
			'defaultname' => 'Unknown',
			'href' => '',
		))) . '
			</a>
			<div class="menu" data-menu="menu" aria-hidden="true" data-href="' . $__templater->func('link', array('chat/user/menu-options', $__vars['user'], array('room_id' => $__vars['room']['room_id'], ), ), true) . '" data-load-target=".js-siropuRoomMenuBody">
				<div class="menu-content siropuChatUserMenu js-siropuRoomMenuBody">
					<div class="menu-row">' . 'Loading' . $__vars['xf']['language']['ellipsis'] . '</div>
				</div>
			</div>
		';
	} else {
		$__finalCompiled .= '
			' . $__templater->callMacro('siropu_chat_guest_macros', 'username', array(
			'user' => $__vars['user'],
		), $__vars) . '
		';
	}
	$__finalCompiled .= '
		' . $__templater->callMacro('siropu_chat_user_macros', 'activity_status', array(
		'user' => $__vars['user'],
		'roomId' => $__vars['room']['room_id'],
	), $__vars) . '
		' . $__templater->callMacro('siropu_chat_user_macros', 'user_status', array(
		'user' => $__vars['user'],
	), $__vars) . '
	</li>
';
	return $__finalCompiled;
}
),
'conversation' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'conversations' => '!',
		'unread' => '',
		'channel' => 'conversation',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if (!$__templater->test($__vars['conversations'], 'empty', array())) {
		$__finalCompiled .= '
		';
		if ($__templater->isTraversable($__vars['conversations'])) {
			foreach ($__vars['conversations'] AS $__vars['conversation']) {
				$__finalCompiled .= '
			' . $__templater->callMacro(null, 'conv_user', array(
					'conversation' => $__vars['conversation'],
					'user' => $__vars['conversation']['Contact'],
					'unread' => $__vars['unread'],
					'channel' => $__vars['channel'],
				), $__vars) . '
		';
			}
		}
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'conv_user' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'conversation' => '!',
		'user' => '!',
		'unread' => '',
		'channel' => 'conversation',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<li data-conv-id="' . $__templater->escape($__vars['conversation']['conversation_id']) . '" data-username="' . $__templater->filter($__vars['user']['username'], array(array('for_attr', array()),), true) . '" data-title="' . $__templater->filter('Conversation with ' . $__vars['user']['username'] . '', array(array('for_attr', array()),), true) . '" class="' . (($__vars['unread'] AND ($__templater->func('count', array($__vars['unread'][$__vars['conversation']['conversation_id']], ), false) ? 'siropuChatNewMessage ' : '')) . ((($__templater->method($__vars['xf']['visitor'], 'getLastConvIdSiropuChat', array()) == $__vars['conversation']['conversation_id']) AND ($__vars['channel'] == 'conversation')) ? 'siropuChatActiveConversation' : '')) . '">
		';
	if ($__vars['xf']['options']['siropuChatAvatarInUserList']) {
		$__finalCompiled .= '
			' . $__templater->func('avatar', array($__vars['user'], 'xxs', false, array(
			'defaultname' => 'Unknown',
			'itemprop' => 'image',
		))) . '
		';
	}
	$__finalCompiled .= '
		' . $__templater->func('username_link', array($__vars['user'], true, array(
		'defaultname' => 'Unknown',
		'href' => '',
	))) . '
		' . $__templater->callMacro('siropu_chat_user_macros', 'activity_status', array(
		'user' => $__vars['user'],
	), $__vars) . '
		<span class="siropuChatConversationActions">
			<a href="' . $__templater->func('link', array('chat/conversation/leave', $__vars['conversation'], ), true) . '" class="siropuChatLeaveConversation" title="' . $__templater->filter('Leave conversation', array(array('for_attr', array()),), true) . '" data-xf-init="tooltip" data-xf-click="overlay">' . $__templater->fontAwesome('far fa-times-circle', array(
	)) . '</a>
			<a href="' . $__templater->func('link', array('chat/conversation/view', $__vars['conversation'], array('fullpage' => 'true', ), ), true) . '" class="siropuChatConversationPopup" data-xf-click="siropu-chat-popup" title="' . $__templater->filter('Open in popup', array(array('for_attr', array()),), true) . '" data-xf-init="tooltip">' . $__templater->fontAwesome('far fa-external-link-alt', array(
	)) . '</a>
		</span>
		' . $__templater->callMacro('siropu_chat_user_macros', 'user_status', array(
		'user' => $__vars['user'],
	), $__vars) . '
	</li>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . '

' . '

';
	return $__finalCompiled;
}
);