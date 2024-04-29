<?php
// FROM HASH: 06462fe196d76400c62e9393c6e241dd
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['urlType'] == 'upgradeGroup') {
		$__finalCompiled .= '
';
		$__compilerTemp1 = '';
		if ($__vars['upgradeUserGroup']['usg_id']) {
			$__compilerTemp1 .= ' ' . 'Edit UserGroup' . '
  ';
		} else {
			$__compilerTemp1 .= ' ' . 'Add Usergroup' . ' ';
		}
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('
  ' . $__compilerTemp1 . '
');
		$__finalCompiled .= '
	';
	} else {
		$__finalCompiled .= '
  ';
		$__compilerTemp2 = '';
		if ($__vars['upgradeUserGroup']['usg_id']) {
			$__compilerTemp2 .= ' ' . 'Edit UserGroup' . ' ';
		} else {
			$__compilerTemp2 .= ' ' . 'Change usergroup' . ' ';
		}
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('
  ' . $__compilerTemp2 . '
');
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '

';
	$__compilerTemp3 = array(array(
		'value' => '0',
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'None' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	));
	if ($__templater->isTraversable($__vars['userGroups'])) {
		foreach ($__vars['userGroups'] AS $__vars['key'] => $__vars['group']) {
			$__compilerTemp3[] = array(
				'value' => $__vars['key'],
				'selected' => ($__vars['upgradeUserGroup']['current_userGroup'] == $__vars['key']),
				'label' => $__templater->escape($__vars['group']),
				'_type' => 'option',
			);
		}
	}
	$__compilerTemp4 = '';
	if ($__vars['urlType'] == 'upgradeGroup') {
		$__compilerTemp4 .= '
        ' . $__templater->formRow('
          ' . $__templater->formNumberBoxRow(array(
			'name' => 'total_message',
			'placeholder' => '123...!',
			'data-i' => '0',
			'value' => $__vars['upgradeUserGroup']['message_count'],
		), array(
			'rowtype' => 'fullWidth',
		)) . '
        ', array(
			'rowtype' => 'input',
			'label' => 'Enter Total Messages',
			'hint' => 'Required',
		)) . '
        ';
	} else {
		$__compilerTemp4 .= '
        ' . $__templater->formRow('
          ' . $__templater->formNumberBoxRow(array(
			'name' => 'last_login',
			'placeholder' => 'days...!',
			'data-i' => '0',
			'value' => $__vars['upgradeUserGroup']['last_login'],
		), array(
			'rowtype' => 'fullWidth',
		)) . '
        ', array(
			'rowtype' => 'input',
			'label' => 'Last login days',
			'hint' => 'Required',
		)) . '
      ';
	}
	$__compilerTemp5 = array(array(
		'value' => '0',
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'None' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	));
	if ($__templater->isTraversable($__vars['userGroups'])) {
		foreach ($__vars['userGroups'] AS $__vars['key'] => $__vars['group']) {
			$__compilerTemp5[] = array(
				'value' => $__vars['key'],
				'selected' => ($__vars['upgradeUserGroup']['upgrade_userGroup'] == $__vars['key']),
				'label' => $__templater->escape($__vars['group']),
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->form('
  <div class="block-container">
    <div class="block-body">
      ' . $__templater->formSelectRow(array(
		'name' => 'sl_ug_id',
		'required' => 'required',
	), $__compilerTemp3, array(
		'label' => 'Select Usergroup',
		'hint' => 'Required',
	)) . '
      ' . $__compilerTemp4 . '

      ' . $__templater->formSelectRow(array(
		'name' => 'up_ug_id',
		'required' => 'required',
	), $__compilerTemp5, array(
		'label' => 'Upgrade Usergroup',
		'hint' => 'Required',
	)) . '
    </div>
    ' . $__templater->formSubmitRow(array(
		'submit' => '',
		'icon' => 'save',
	), array(
	)) . '
  </div>
', array(
		'action' => $__templater->func('link', array(('' . $__vars['urlType']) . '/save', $__vars['upgradeUserGroup'], ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-force-flash-message' => 'true',
	));
	return $__finalCompiled;
}
);