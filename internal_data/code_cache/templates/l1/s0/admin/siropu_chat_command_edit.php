<?php
// FROM HASH: 960dff0c3b424266d9c38e73a963f663
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['command'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add command');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit command' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['command']['command_name']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['command'], 'isUpdate', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('chat/commands/delete', $__vars['command'], ), false),
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
	$__compilerTemp3 = '';
	if ($__vars['command']['command_options_template']) {
		$__compilerTemp3 .= '
				<hr class="formRowSep" />
				
				' . $__templater->includeTemplate($__vars['command']['command_options_template'], $__vars) . '

				<hr class="formRowSep" />
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formTextBoxRow(array(
		'name' => 'command_name',
		'value' => $__vars['command']['command_name'],
		'maxlength' => $__templater->func('max_length', array($__vars['command'], 'command_name', ), false),
	), array(
		'label' => 'Command name',
		'explain' => 'The name of the command that will be used after the /',
	)) . '

			' . $__templater->formCodeEditorRow(array(
		'name' => 'command_description',
		'value' => $__vars['command']['command_description'],
		'mode' => 'html',
		'data-line-wrapping' => 'true',
		'class' => 'codeEditor--autoSize codeEditor--proportional',
	), array(
		'label' => 'Command description',
		'explain' => 'Information on how to use the command. This can be simple text, HTML or a phrase - <b>phrase:my_phrase_title</b>',
	)) . '

			' . $__templater->formRow('
				' . $__templater->callMacro('helper_callback_fields', 'callback_fields', array(
		'data' => $__vars['command'],
		'namePrefix' => 'command_callback',
	), $__vars) . '
			', array(
		'rowtype' => 'input',
		'label' => 'Execute callback',
	)) . '

			' . $__templater->formSelectRow(array(
		'name' => 'command_rooms',
		'value' => $__vars['command']['command_rooms'],
		'multiple' => 'true',
	), $__compilerTemp1, array(
		'label' => 'Enable for selected rooms',
		'explain' => 'This option is not required.',
	)) . '

			' . $__templater->formSelectRow(array(
		'name' => 'command_user_groups',
		'value' => $__vars['command']['command_user_groups'],
		'multiple' => 'true',
	), $__compilerTemp2, array(
		'label' => 'Enable for selected user groups',
		'explain' => 'This option is not required.',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'command_options_template',
		'value' => $__vars['command']['command_options_template'],
		'maxlength' => $__templater->func('max_length', array($__vars['command'], 'command_options_template', ), false),
		'data-xf-init' => 'siropu-chat-command-options-template',
	), array(
		'label' => 'Command options template',
		'explain' => 'Allows you to include a template that contains your custom command options that you can use in the callback.',
	)) . '

			' . $__compilerTemp3 . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'command_enabled',
		'value' => '1',
		'selected' => $__vars['command']['command_enabled'],
		'label' => 'Command is enabled',
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
		'action' => $__templater->func('link', array('chat/commands/save', $__vars['command'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);