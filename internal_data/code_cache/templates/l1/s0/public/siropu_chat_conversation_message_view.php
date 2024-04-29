<?php
// FROM HASH: 7de05df0aaf890a925fc0faa3c5f3e79
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Viewing message by ' . $__templater->escape($__vars['message']['message_username']) . '');
	$__finalCompiled .= '
';
	$__templater->pageParams['pageDescription'] = $__templater->preEscaped('<a href="' . $__templater->func('link', array('chat/conversation/view', $__vars['message']['Conversation'], ), true) . '" class="u-concealed">' . 'Conversation with ' . $__templater->escape($__vars['message']['Conversation']['Contact']['username']) . '' . '</a>');
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
			<ul class="siropuChatConversation siropuChatMessages" data-conversation-id="' . $__templater->escape($__vars['message']['message_conversation_id']) . '" data-xf-init="siropu-chat-messages">
				' . $__templater->includeTemplate('siropu_chat_conversation_message_row', $__vars) . '
			</ul>
		</div>
		<div class="block-footer">
			<span class="block-footer-controls">
				' . $__templater->button('Go to conversation', array(
		'href' => $__templater->func('link', array('chat/conversation/view', $__vars['message']['Conversation'], ), false),
		'fa' => 'fas fa-arrow-alt-circle-right',
	), '', array(
	)) . '
			</span>
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);