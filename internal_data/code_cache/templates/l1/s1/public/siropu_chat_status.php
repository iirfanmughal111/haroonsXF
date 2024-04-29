<?php
// FROM HASH: 69a8ccab8f636d058ac736b5893ef69c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Update status');
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formTextAreaRow(array(
		'name' => 'status',
		'value' => $__vars['xf']['visitor']['siropu_chat_status'],
		'maxlength' => $__vars['xf']['options']['siropuChatStatusMaxLength'],
		'autosize' => 'true',
	), array(
		'label' => 'Status',
		'explain' => 'Your status will be displayed under your username in chatters list. Max ' . $__templater->escape($__vars['xf']['options']['siropuChatStatusMaxLength']) . ' characters.',
	)) . '
		</div>
	</div>
	' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
	)) . '
', array(
		'action' => $__templater->func('link', array('chat/update-status', ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);