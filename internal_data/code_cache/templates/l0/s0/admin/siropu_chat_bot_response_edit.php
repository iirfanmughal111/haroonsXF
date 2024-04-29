<?php
// FROM HASH: cd10ab9f92664e1a5f21c6f978006e8c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['response'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add bot response');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit bot response' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['response']['response_keyword']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['response'], 'isUpdate', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('chat/bot-messages/delete', $__vars['response'], ), false),
			'icon' => 'delete',
			'overlay' => 'true',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

';
	$__compilerTemp1 = array();
	if ($__templater->isTraversable($__vars['rooms'])) {
		foreach ($__vars['rooms'] AS $__vars['room']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['room']['room_id'],
				'label' => $__templater->escape($__vars['room']['room_name']),
				'_type' => 'option',
			);
		}
	}
	$__compilerTemp2 = array();
	if ($__templater->isTraversable($__vars['userGroups'])) {
		foreach ($__vars['userGroups'] AS $__vars['userGroupId'] => $__vars['userGroupTitle']) {
			$__compilerTemp2[] = array(
				'value' => $__vars['userGroupId'],
				'label' => $__templater->escape($__vars['userGroupTitle']),
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formTextAreaRow(array(
		'name' => 'response_keyword',
		'value' => $__vars['response']['response_keyword'],
		'rows' => '3',
	), array(
		'label' => 'Keyword',
		'explain' => 'The keyword that will trigger the response. You can add multiple keywords by placing each one on a new line.',
	)) . '

			' . $__templater->formTextAreaRow(array(
		'name' => 'response_message',
		'value' => $__vars['response']['response_message'],
		'rows' => '3',
	), array(
		'label' => 'Response',
		'explain' => 'The response that will be returned. You can add multiple responses by placing each one on a new line.',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'response_bot_name',
		'value' => $__vars['response']['response_bot_name'],
		'maxlength' => $__templater->func('max_length', array($__vars['response'], 'response_bot_name', ), false),
	), array(
		'label' => 'Bot name',
		'explain' => 'The name of the bot that will return the response.',
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formSelectRow(array(
		'name' => 'response_rooms',
		'value' => $__vars['response']['response_rooms'],
		'multiple' => 'true',
	), $__compilerTemp1, array(
		'label' => 'Enable for selected rooms',
		'explain' => 'This option is not required.',
	)) . '

			' . $__templater->formSelectRow(array(
		'name' => 'response_user_groups',
		'value' => $__vars['response']['response_user_groups'],
		'multiple' => 'true',
	), $__compilerTemp2, array(
		'label' => 'Enable for selected user groups',
		'explain' => 'This option is not required.',
	)) . '

			<hr class="formRowSep" />
			
			' . $__templater->formNumberBoxRow(array(
		'name' => 'response_settings[interval]',
		'value' => $__vars['response']['response_settings']['interval'],
		'min' => '0',
		'step' => '1',
	), array(
		'label' => 'Minimum interval between responses',
		'explain' => 'The minimum interval in minutes between responses.',
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'response_settings[exact_match]',
		'value' => '1',
		'selected' => $__vars['response']['response_settings']['exact_match'],
		'label' => 'Exact keyword match',
		'_type' => 'option',
	),
	array(
		'name' => 'response_settings[mention]',
		'value' => '1',
		'selected' => $__vars['response']['response_settings']['mention'],
		'label' => 'Mention user',
		'_type' => 'option',
	)), array(
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('chat/bot-responses/save', $__vars['response'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);