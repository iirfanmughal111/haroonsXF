<?php
// FROM HASH: c382324631b101d0a9318a1e114efe1d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['xf']['visitor'], 'canWhisperSiropuChat', array()) AND ($__vars['xf']['visitor']['user_id'] != $__vars['user']['user_id'])) {
		$__finalCompiled .= '
	<a role="button" class="menu-linkRow" data-xf-click="siropu-chat-whisper" data-username="' . $__templater->filter($__vars['user']['username'], array(array('for_attr', array()),), true) . '">' . 'Whisper' . '</a>
';
	}
	$__finalCompiled .= '
';
	if ($__templater->method($__vars['xf']['visitor'], 'canMessageSiropuChatUser', array($__vars['user'], )) AND (!$__templater->method($__vars['xf']['visitor'], 'isInConversationWithSiropuChatUser', array($__vars['user'], )))) {
		$__finalCompiled .= '
	<a role="button" class="menu-linkRow" data-xf-click="siropu-chat-toggle-conv-form">' . 'Start private conversation' . '</a>
	' . $__templater->callMacro('siropu_chat_conversation_start', 'form', array(
			'recipient' => $__vars['user'],
		), $__vars) . '
';
	}
	$__finalCompiled .= '
';
	if ($__templater->method($__vars['xf']['visitor'], 'canSanctionSiropuChatUser', array($__vars['user'], ))) {
		$__finalCompiled .= '
	<a href="' . $__templater->func('link', array('chat/sanction', array('room_id' => $__vars['room']['room_id'], 'user_id' => $__vars['user']['user_id'], 'username' => $__vars['user']['username'], ), ), true) . '" class="menu-linkRow" data-xf-click="overlay">' . 'Sanction' . '</a>
';
	} else if ($__templater->method($__vars['xf']['visitor'], 'canRoomAuthorSanctionSiropuChatUser', array($__vars['user'], $__vars['room']['room_user_id'], ))) {
		$__finalCompiled .= '
	<a href="' . $__templater->func('link', array('chat/room/sanction', array('room_id' => $__vars['room']['room_id'], 'user_id' => $__vars['user']['user_id'], 'username' => $__vars['user']['username'], ), ), true) . '" class="menu-linkRow" data-xf-click="overlay">' . 'Sanction' . '</a>
';
	}
	$__finalCompiled .= '
';
	if ($__vars['xf']['options']['siropuChatRooms'] AND (($__vars['user']['user_id'] == $__vars['xf']['visitor']['user_id']) AND ($__vars['room']['room_leave'] AND (!$__templater->method($__vars['xf']['visitor'], 'isInShoutboxModeSiropuChat', array()))))) {
		$__finalCompiled .= '
	<a href="' . $__templater->func('link', array('chat/room/leave', array('room_id' => $__vars['room']['room_id'], ), ), true) . '" class="menu-linkRow" data-xf-click="siropu-chat-leave-room">' . 'Leave room' . '</a>
';
	}
	$__finalCompiled .= '
';
	if ($__templater->method($__vars['xf']['visitor'], 'canResetSiropuChatUserData', array())) {
		$__finalCompiled .= '
	<a href="' . $__templater->func('link', array('chat/user/reset-data', $__vars['user'], ), true) . '" class="menu-linkRow" data-xf-click="overlay">' . 'Reset chat data' . '</a>
';
	}
	$__finalCompiled .= '
';
	if ($__vars['xf']['visitor']['user_id']) {
		$__finalCompiled .= '
	<a href="' . $__templater->func('link', array('members', $__vars['user'], ), true) . '" class="menu-linkRow" data-xf-click="overlay">' . 'View public profile' . '</a>
';
	}
	return $__finalCompiled;
}
);