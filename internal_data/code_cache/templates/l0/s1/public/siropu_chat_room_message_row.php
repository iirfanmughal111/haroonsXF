<?php
// FROM HASH: cfb04a825791ba1b9452a86cdcc5d242
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<li' . ($__vars['isArchive'] ? ((' id="rm-' . $__templater->escape($__vars['message']['message_id'])) . '"') : '') . ' class="' . $__templater->escape($__vars['message']['css_class']) . ' js-lbContainer" data-id="' . $__templater->escape($__vars['message']['message_id']) . '" data-user-id="' . $__templater->escape($__vars['message']['message_user_id']) . '">
	<div class="siropuChatMessageContentLeft">
		';
	if ($__templater->method($__vars['message'], 'isIgnored', array())) {
		$__finalCompiled .= '
			' . $__templater->fontAwesome('fa-eye-slash', array(
			'style' => 'color: crimson;',
		)) . '
		';
	}
	$__finalCompiled .= '
		';
	if ($__vars['canMassDelete']) {
		$__finalCompiled .= '
			' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'name' => 'message_ids[]',
			'value' => $__vars['message']['message_id'],
			'_type' => 'option',
		))) . '
		';
	}
	$__finalCompiled .= '
		' . $__templater->callMacro('siropu_chat_room_message_helper', 'message_content', array(
		'message' => $__vars['message'],
	), $__vars) . '
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
				'link' => 'chat/message/react',
				'list' => '< .siropuChatMessageRow | .siropuChatReactions',
			))) . '
						';
		} else {
			$__compilerTemp1 .= '
							';
			if ($__templater->method($__vars['message'], 'isLiked', array())) {
				$__compilerTemp1 .= '
								<a href="' . $__templater->func('link', array('chat/message/unlike', $__vars['message'], ), true) . '" title="' . $__templater->filter('Unlike', array(array('for_attr', array()),), true) . '" data-xf-click="siropu-chat-unlike">' . $__templater->fontAwesome('fa-thumbs-down', array(
				)) . ' <span>' . 'Unlike' . '</span></a>
							';
			} else {
				$__compilerTemp1 .= '
								<a href="' . $__templater->func('link', array('chat/message/like', $__vars['message'], ), true) . '" title="' . $__templater->filter('Like', array(array('for_attr', array()),), true) . '" data-xf-click="siropu-chat-like">' . $__templater->fontAwesome('fa-thumbs-up', array(
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
						<a href="' . $__templater->func('link', array('chat/message/quote', $__vars['message'], ), true) . '" title="' . $__templater->filter('Quote', array(array('for_attr', array()),), true) . '" data-xf-click="siropu-chat-quote">' . $__templater->fontAwesome('fa-quote-right', array(
		)) . ' <span>' . 'Quote' . '</span></a>
					';
	}
	$__compilerTemp1 .= '
					';
	if ($__templater->method($__vars['message'], 'canLink', array())) {
		$__compilerTemp1 .= '
						<a href="' . $__templater->func('link', array('chat/message/link', $__vars['message'], ), true) . '" title="' . $__templater->filter('Link', array(array('for_attr', array()),), true) . '" data-xf-click="overlay" data-cache="false">' . $__templater->fontAwesome('fa-link', array(
		)) . ' <span>' . 'Link' . '</span></a>
					';
	}
	$__compilerTemp1 .= '
					';
	if ($__templater->method($__vars['message'], 'canReport', array())) {
		$__compilerTemp1 .= '
						<a href="' . $__templater->func('link', array('chat/message/report', $__vars['message'], ), true) . '" title="' . $__templater->filter('Report', array(array('for_attr', array()),), true) . '" data-xf-click="overlay" data-cache="false">' . $__templater->fontAwesome('fa-flag', array(
		)) . ' <span>' . 'Report' . '</span></a>
					';
	}
	$__compilerTemp1 .= '
					';
	if ($__templater->method($__vars['message'], 'canEdit', array())) {
		$__compilerTemp1 .= '
						<a href="' . $__templater->func('link', array('chat/message/edit', $__vars['message'], ), true) . '" title="' . $__templater->filter('Edit', array(array('for_attr', array()),), true) . '" data-xf-click="overlay" data-cache="false">' . $__templater->fontAwesome('fa-edit', array(
		)) . ' <span>' . 'Edit' . '</span></a>
					';
	}
	$__compilerTemp1 .= '
					';
	if ($__templater->method($__vars['message'], 'canDelete', array())) {
		$__compilerTemp1 .= '
						<a href="' . $__templater->func('link', array('chat/message/delete', $__vars['message'], ), true) . '" title="' . $__templater->filter('Delete', array(array('for_attr', array()),), true) . '" data-xf-click="overlay" data-cache="false">' . $__templater->fontAwesome('fa-trash', array(
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