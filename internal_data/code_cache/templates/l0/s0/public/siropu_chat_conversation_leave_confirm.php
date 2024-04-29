<?php
// FROM HASH: 70354af13d2c59b39195d8bffc120b21
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Leave conversation' . ' (' . $__templater->func('username_link', array($__vars['conversation']['Contact'], true, array(
		'defaultname' => 'Unknown',
		'href' => '',
	))) . ')');
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		' . $__templater->formInfoRow('
			' . 'If you leave the conversation, you will no longer receive new messages from <b>' . $__templater->escape($__vars['conversation']['Contact']['username']) . '</b>. You can restart the conversation anytime, but if <b>' . $__templater->escape($__vars['conversation']['Contact']['username']) . '</b> leaves the conversation as well, all messages will be deleted from the conversation history.' . '
		', array(
		'rowtype' => 'confirm',
	)) . '
		' . $__templater->formSubmitRow(array(
		'submit' => 'Leave conversation',
		'class' => 'js-overlayClose',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('chat/conversation/leave', $__vars['conversation'], ), false),
		'class' => 'block',
		'data-xf-init' => 'siropu-chat-leave-conversation',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);