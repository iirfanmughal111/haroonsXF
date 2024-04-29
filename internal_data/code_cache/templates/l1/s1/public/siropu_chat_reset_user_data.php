<?php
// FROM HASH: 41d160946950bf5ae1054a672140cd16
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Reset chat data for ' . $__templater->escape($__vars['user']['username']) . '');
	$__finalCompiled .= '

';
	$__templater->inlineJs('
	$(document).on(\'ajax:complete\', function(e, xhr, status)
	{
		var data = xhr.responseJSON;

		if (data.isSelf && data.noConversations !== undefined)
		{
			$(\'.siropuChatConversation.siropuChatMessages\').remove();
			$(\'.siropuChatConversation.siropuChatUsers\').html(data.noConversations);
			XF.activate($(\'.siropuChatConversation.siropuChatUsers\'));
		}
	});
');
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'data[settings]',
		'value' => '1',
		'checked' => 'true',
		'label' => 'Settings',
		'_type' => 'option',
	),
	array(
		'name' => 'data[status]',
		'value' => '1',
		'checked' => 'true',
		'label' => 'Status',
		'_type' => 'option',
	),
	array(
		'name' => 'data[message_count]',
		'value' => '1',
		'checked' => 'true',
		'label' => 'Message count',
		'_type' => 'option',
	),
	array(
		'name' => 'data[rooms]',
		'value' => '1',
		'checked' => 'true',
		'label' => 'Rooms',
		'_type' => 'option',
	),
	array(
		'name' => 'data[conversations]',
		'value' => '1',
		'checked' => 'true',
		'label' => 'Private conversations',
		'_type' => 'option',
	)), array(
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'submit' => 'Reset',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('chat/user/reset-data', $__vars['user'], ), false),
		'class' => 'block',
		'id' => 'siropuChatReset',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);