<?php
// FROM HASH: 1c0caf046a7f0c8696a87e8f0d0089c0
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit message');
	$__finalCompiled .= '

';
	$__templater->includeJs(array(
		'src' => 'siropu/chat/message.js',
		'min' => 'true',
	));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['message'], 'canChangeAuthor', array())) {
		$__compilerTemp1 .= '
				' . $__templater->formTextBoxRow(array(
			'name' => 'username',
			'value' => $__vars['message']['message_username'],
		), array(
			'label' => 'Author',
		)) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formEditorRow(array(
		'name' => 'message',
		'value' => $__vars['message']['message_text'],
		'rows' => '1',
		'removebuttons' => $__vars['disabledButtons'],
		'data-min-height' => '40',
	), array(
		'rowtype' => 'fullWidth noLabel',
	)) . '
			' . $__compilerTemp1 . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'class' => 'js-overlayClose',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('chat/message/edit', $__vars['message'], ), false),
		'class' => 'block',
		'data-edit' => 'true',
		'data-xf-init' => 'siropu-chat-message-save siropu-chat-form',
		'data-multi-line' => ($__vars['xf']['options']['siropuChatMultiLineMessages'] ? 'true' : 'false'),
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);