<?php
// FROM HASH: 8f6767711898ec304b9e2513c8262414
return array(
'macros' => array('message_content' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'message' => '!',
		'lastRow' => '',
		'isResponsive' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__compilerTemp1 = '';
	if (($__vars['xf']['options']['siropuChatUserTagging'] == 'button') AND (!$__templater->method($__vars['message'], 'isSelf', array()))) {
		$__compilerTemp1 .= '
			<a role="button" class="siropuChatTag" title="' . $__templater->filter('Tag user', array(array('for_attr', array()),), true) . '">@</a>
		';
	}
	$__compilerTemp2 = '';
	if ($__vars['message']['User']) {
		$__compilerTemp2 .= '
			';
		if (($__vars['xf']['options']['siropuChatUserTagging'] == 'username') AND (!$__templater->method($__vars['message'], 'isSelf', array()))) {
			$__compilerTemp2 .= '
				' . $__templater->func('username_link', array($__vars['message']['User'], true, array(
				'defaultname' => $__vars['message']['message_username'],
				'class' => 'siropuChatUserTag',
				'notooltip' => 'true',
				'title' => $__templater->filter('Tag user', array(array('for_attr', array()),), false),
			))) . '
			';
		} else {
			$__compilerTemp2 .= '
				' . $__templater->func('username_link', array($__vars['message']['User'], true, array(
				'defaultname' => $__vars['message']['message_username'],
			))) . '
			';
		}
		$__compilerTemp2 .= '
		';
	} else {
		$__compilerTemp2 .= '
			' . $__templater->callMacro('siropu_chat_guest_macros', 'username', array(
			'user' => array('username' => $__vars['message']['message_username'], ),
		), $__vars) . '
		';
	}
	$__vars['richUsername'] = $__templater->preEscaped(trim('
		' . $__compilerTemp1 . '
		' . $__compilerTemp2 . '
	'));
	$__finalCompiled .= '

	';
	$__vars['botName'] = ($__vars['message']['message_bot_name'] ?: $__vars['xf']['options']['siropuChatBotName']);
	$__finalCompiled .= '

	';
	if ($__vars['xf']['options']['siropuChatAvatarInMessageList']) {
		$__finalCompiled .= '
		';
		if ($__vars['xf']['options']['siropuChatBotAvatar'] AND ($__templater->func('in_array', array($__vars['message']['message_type'], array('bot', 'prune', 'command', ), ), false) OR ($__templater->method($__vars['message'], 'isBotNotification', array()) AND $__vars['xf']['options']['siropuChatActivityNotificationsBot']))) {
			$__finalCompiled .= '
			<a class="avatar avatar--xxs"><img src="' . $__templater->escape($__vars['xf']['options']['siropuChatBotAvatar']) . '" alt="' . $__templater->escape($__vars['botName']) . '" itemprop="image"></a>
		';
		} else if ($__vars['message']['User']) {
			$__finalCompiled .= '
			' . $__templater->func('avatar', array($__vars['message']['User'], 'xxs', false, array(
				'defaultname' => $__vars['message']['message_username'],
				'itemprop' => 'image',
			))) . '
		';
		} else {
			$__finalCompiled .= '
			' . $__templater->func('avatar', array($__vars['message']['User'], 'xxs', false, array(
				'defaultname' => $__vars['message']['message_username'],
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
	if ($__templater->method($__vars['message'], 'isAnnouncement', array()) AND $__vars['message']['message_bot_name']) {
		$__finalCompiled .= '
		' . $__templater->filter($__vars['botName'], array(array('raw', array()),), true) . ':
	';
	} else if ($__templater->method($__vars['message'], 'isBot', array()) OR $__templater->method($__vars['message'], 'isCommand', array())) {
		$__finalCompiled .= '
		';
		if ($__templater->method($__vars['message'], 'isBotNotification', array()) AND (!$__vars['xf']['options']['siropuChatActivityNotificationsBot'])) {
			$__finalCompiled .= '
			' . $__templater->escape($__vars['richUsername']) . '
		';
		} else {
			$__finalCompiled .= '
			' . $__templater->filter($__vars['botName'], array(array('raw', array()),), true) . ':
		';
		}
		$__finalCompiled .= '
	';
	} else if ($__templater->method($__vars['message'], 'isMe', array())) {
		$__finalCompiled .= '
		' . $__templater->filter($__vars['botName'], array(array('raw', array()),), true) . ': ' . $__templater->escape($__vars['richUsername']) . '
	';
	} else if ($__templater->method($__vars['message'], 'isSlap', array()) OR $__templater->method($__vars['message'], 'isHug', array())) {
		$__finalCompiled .= '
		' . $__templater->filter($__vars['botName'], array(array('raw', array()),), true) . ':
	';
	} else {
		$__finalCompiled .= '
		' . $__templater->escape($__vars['richUsername']) . ':
	';
	}
	$__finalCompiled .= '
	';
	if ($__templater->method($__vars['message'], 'isWhisper', array())) {
		$__finalCompiled .= '
		<span class="siropuChatRecipients' . ($__templater->method($__vars['message'], 'isRecipient', array()) ? '' : ' siropuChatNotRecipient') . '" title="' . $__templater->filter($__vars['message']['recipients'], array(array('for_attr', array()),), true) . '" data-recipients="' . $__templater->filter($__vars['message']['recipients'], array(array('for_attr', array()),), true) . '" data-xf-init="tooltip">(' . 'Whisper' . ')</span>
	';
	}
	$__finalCompiled .= '
	<span class="siropuChatMessageText">';
	if ($__templater->method($__vars['message'], 'isError', array())) {
		$__finalCompiled .= '(Error)' . ' ';
	}
	$__finalCompiled .= ($__vars['lastRow'] ? $__templater->func('smilie', array($__templater->func('snippet', array($__vars['message']['message'], ($__vars['isResponsive'] ? 25 : 100), array('stripBbCode' => true, ), ), false), ), true) : $__templater->func('bb_code', array($__vars['message']['message'], 'siropu_chat_room_message', $__vars['message'], ), true)) . '</span>
	';
	if ($__vars['xf']['options']['siropuChatReactions']) {
		$__finalCompiled .= '
		<span class="siropuChatReactions">' . $__templater->func('reactions', array($__vars['message'], 'chat/message/reactions', array())) . '</span>
	';
	} else if ($__templater->method($__vars['message'], 'hasLikes', array())) {
		$__finalCompiled .= '
		<a href="' . $__templater->func('link', array('chat/message/likes', $__vars['message'], ), true) . '" class="siropuChatMessageLikes" data-xf-click="overlay" data-cache="false">+' . $__templater->escape($__vars['message']['message_like_count']) . '</a>
	';
	}
	$__finalCompiled .= '
	';
	if ($__templater->method($__vars['message'], 'isEdited', array()) AND $__templater->method($__vars['message'], 'canViewHistory', array())) {
		$__finalCompiled .= '
		<a href="' . $__templater->func('link', array('chat/message/history', $__vars['message'], ), true) . '" class="siropuChatMessageEdited" title="' . $__templater->filter('View history', array(array('for_attr', array()),), true) . '" data-xf-init="tooltip" data-xf-click="overlay" data-cache="false">' . 'Edited' . '</a>
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