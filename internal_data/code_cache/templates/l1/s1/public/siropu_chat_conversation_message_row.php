<?php
// FROM HASH: 0227adffe0fe46bc69beef6f9d36d6cf
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<li class="' . $__templater->escape($__vars['message']['css_class']) . ' js-lbContainer" data-id="' . $__templater->escape($__vars['message']['message_id']) . '">
	<div class="siropuChatMessageContentLeft">
		';
	if ($__vars['xf']['options']['siropuChatAvatarInMessageList']) {
		$__finalCompiled .= '
			' . $__templater->func('avatar', array($__vars['message']['User'], 'xxs', false, array(
			'defaultname' => $__vars['message']['message_username'],
			'itemprop' => 'image',
		))) . '
		';
	}
	$__finalCompiled .= '
		';
	if ($__templater->method($__vars['message'], 'isBot', array())) {
		$__finalCompiled .= '
			' . $__templater->filter($__vars['xf']['options']['siropuChatBotName'], array(array('raw', array()),), true) . ':
		';
	} else {
		$__finalCompiled .= '
			' . $__templater->func('username_link', array($__vars['message']['User'], true, array(
			'defaultname' => $__vars['message']['message_username'],
		))) . ':
		';
	}
	$__finalCompiled .= '
		<span class="siropuChatMessageText">';
	if ($__templater->method($__vars['message'], 'isError', array())) {
		$__finalCompiled .= '(Error)' . ' ';
	}
	$__finalCompiled .= $__templater->func('bb_code', array($__vars['message']['message'], 'siropu_chat_conv_message', $__vars['message']['User'], ), true) . '</span>
		';
	if ($__vars['xf']['options']['siropuChatReactions']) {
		$__finalCompiled .= '
			<span class="siropuChatReactions">' . $__templater->func('reactions_summary', array($__vars['message']['reactions'])) . '</span>
		';
	} else if ($__templater->method($__vars['message'], 'isLiked', array())) {
		$__finalCompiled .= '
			<a role="button" class="siropuChatMessageLikes">+1</a>
		';
	}
	$__finalCompiled .= '
	</div>
	<div class="siropuChatMessageContentRight">
		';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
					';
	if ($__templater->method($__vars['message'], 'canLike', array())) {
		$__compilerTemp1 .= '
						';
		if ($__vars['xf']['options']['siropuChatReactions']) {
			$__compilerTemp1 .= '
							' . $__templater->func('react', array(array(
				'content' => $__vars['message'],
				'link' => 'chat/conversation/react',
				'list' => '< .siropuChatMessageRow | .siropuChatReactions',
			))) . '
						';
		} else {
			$__compilerTemp1 .= '
							';
			if ($__templater->method($__vars['message'], 'isLiked', array())) {
				$__compilerTemp1 .= '
								<a href="' . $__templater->func('link', array('chat/conversation/unlike', $__vars['message'], ), true) . '" title="' . $__templater->filter('Unlike', array(array('for_attr', array()),), true) . '" data-xf-click="siropu-chat-unlike">' . $__templater->fontAwesome('fa-thumbs-down', array(
				)) . ' <span>' . 'Unlike' . '</span></a>
							';
			} else {
				$__compilerTemp1 .= '
								<a href="' . $__templater->func('link', array('chat/conversation/like', $__vars['message'], ), true) . '" title="' . $__templater->filter('Like', array(array('for_attr', array()),), true) . '" data-xf-click="siropu-chat-like">' . $__templater->fontAwesome('fa-thumbs-up', array(
				)) . ' <span>' . 'Like' . '</span></a>
							';
			}
			$__compilerTemp1 .= '
						';
		}
		$__compilerTemp1 .= '
					';
	}
	$__compilerTemp1 .= '
					';
	if ($__templater->method($__vars['message'], 'canQuote', array())) {
		$__compilerTemp1 .= '
						<a href="' . $__templater->func('link', array('chat/conversation/quote', $__vars['message'], ), true) . '" title="' . $__templater->filter('Quote', array(array('for_attr', array()),), true) . '" data-xf-click="siropu-chat-quote">' . $__templater->fontAwesome('fa-quote-right', array(
		)) . ' <span>' . 'Quote' . '</span></a>
					';
	}
	$__compilerTemp1 .= '
					';
	if ($__templater->method($__vars['message'], 'canLink', array())) {
		$__compilerTemp1 .= '
						<a href="' . $__templater->func('link', array('chat/conversation/link', $__vars['message'], ), true) . '" title="' . $__templater->filter('Link', array(array('for_attr', array()),), true) . '" data-xf-click="overlay">' . $__templater->fontAwesome('fa-link', array(
		)) . ' <span>' . 'Link' . '</span></a>
					';
	}
	$__compilerTemp1 .= '
					';
	if ($__templater->method($__vars['message'], 'canReport', array())) {
		$__compilerTemp1 .= '
						<a href="' . $__templater->func('link', array('chat/conversation/report', $__vars['message'], ), true) . '" title="' . $__templater->filter('Report', array(array('for_attr', array()),), true) . '" data-xf-click="overlay" data-cache="false">' . $__templater->fontAwesome('fa-flag', array(
		)) . ' <span>' . 'Report' . '</span></a>
					';
	}
	$__compilerTemp1 .= '
					';
	if ($__templater->method($__vars['message'], 'canEditDelete', array())) {
		$__compilerTemp1 .= '
						<a href="' . $__templater->func('link', array('chat/conversation/edit', $__vars['message'], ), true) . '" title="' . $__templater->filter('Edit', array(array('for_attr', array()),), true) . '" data-xf-click="overlay" data-cache="false">' . $__templater->fontAwesome('fa-edit', array(
		)) . ' <span>' . 'Edit' . '</span></a>
					';
	}
	$__compilerTemp1 .= '
					';
	if ($__templater->method($__vars['message'], 'canEditDelete', array())) {
		$__compilerTemp1 .= '
						<a href="' . $__templater->func('link', array('chat/conversation/delete', $__vars['message'], ), true) . '" title="' . $__templater->filter('Delete', array(array('for_attr', array()),), true) . '" data-xf-click="overlay" data-cache="false">' . $__templater->fontAwesome('fa-trash-alt', array(
		)) . ' <span>' . 'Delete' . '</span></a>
					';
	}
	$__compilerTemp1 .= '
				';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
			<span class="siropuChatMessageActions">
				' . $__compilerTemp1 . '
			</span>
		';
	}
	$__finalCompiled .= '
		' . $__templater->func('date_dynamic', array($__vars['message']['message_date'], array(
		'class' => 'siropuChatDateTime',
	))) . '
	</div>
</li>';
	return $__finalCompiled;
}
);