<?php
// FROM HASH: c78dad9ae54f7a37cac2720e452a2d70
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Viewing message by ' . $__templater->escape($__vars['message']['message_username']) . '');
	$__finalCompiled .= '
';
	$__templater->pageParams['pageDescription'] = $__templater->preEscaped('<a href="' . $__templater->func('link', array('chat/room', $__vars['message']['Room'], ), true) . '" class="u-concealed">' . $__templater->escape($__vars['message']['Room']['room_name']) . '</a>');
	$__templater->pageParams['pageDescriptionMeta'] = true;
	$__finalCompiled .= '

';
	$__templater->includeCss('siropu_chat.less');
	$__finalCompiled .= '

';
	$__templater->includeJs(array(
		'src' => 'siropu/chat/core.min.js',
	));
	$__templater->inlineJs('
	' . $__templater->includeTemplate('siropu_chat_js', $__vars) . '
');
	$__finalCompiled .= '

<div class="block" id="siropuChatViewMessage">
	<div class="block-container">
		<div class="block-body">
			<ul class="siropuChatRoom siropuChatMessages" data-room-id="' . $__templater->escape($__vars['message']['message_room_id']) . '" data-xf-init="siropu-chat-messages">
				' . $__templater->includeTemplate('siropu_chat_room_message_row', $__vars) . '
			</ul>
		</div>
		';
	if ($__templater->method($__vars['xf']['visitor'], 'canViewSiropuChatArchive', array())) {
		$__finalCompiled .= '
			<div class="block-footer">
				<span class="block-footer-controls">
					' . $__templater->button('View message in archive', array(
			'href' => $__templater->func('link', array('chat/archive/message', $__vars['message'], ), false),
			'fa' => 'fas fa-arrow-alt-circle-right',
		), '', array(
		)) . '
				</span>
			</div>
		';
	}
	$__finalCompiled .= '
	</div>
</div>';
	return $__finalCompiled;
}
);