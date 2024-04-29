<?php
// FROM HASH: ebd40a0d0ca1fc185cef52aa219aa843
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = '';
	if ($__vars['autoForumManager']['forum_manage_id']) {
		$__compilerTemp1 .= ' ' . 'Edit Forum' . '
  ';
	} else {
		$__compilerTemp1 .= ' ' . 'Add Forums' . ' ';
	}
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('
  ' . $__compilerTemp1 . '
');
	$__finalCompiled .= '
';
	$__compilerTemp2 = '';
	if ($__vars['autoForumManager']['forum_manage_id']) {
		$__compilerTemp2 .= '
				';
		$__compilerTemp3 = array();
		if ($__templater->isTraversable($__vars['forums'])) {
			foreach ($__vars['forums'] AS $__vars['forum']) {
				$__compilerTemp3[] = array(
					'value' => $__vars['forum']['value'],
					'disabled' => $__vars['forum']['disabled'],
					'selected' => ($__vars['autoForumManager']['node_id'] == $__vars['forum']['value']),
					'label' => $__templater->escape($__vars['forum']['label']),
					'_type' => 'option',
				);
			}
		}
		$__compilerTemp2 .= $__templater->formSelectRow(array(
			'name' => 'listData[]',
			'required' => 'required',
		), $__compilerTemp3, array(
			'label' => 'Select Forums',
			'hint' => 'Required',
		)) . '
			';
	} else {
		$__compilerTemp2 .= '
			';
		$__compilerTemp4 = array();
		if ($__templater->isTraversable($__vars['forums'])) {
			foreach ($__vars['forums'] AS $__vars['forum']) {
				$__compilerTemp4[] = array(
					'value' => $__vars['forum']['value'],
					'disabled' => $__vars['forum']['disabled'],
					'label' => $__templater->escape($__vars['forum']['label']),
					'_type' => 'option',
				);
			}
		}
		$__compilerTemp2 .= $__templater->formSelectRow(array(
			'name' => 'listData[]',
			'multiple' => 'multiple',
			'size' => '7',
			'required' => 'required',
		), $__compilerTemp4, array(
			'label' => 'Select Forums',
			'hint' => 'Required',
		)) . '
		  ';
	}
	$__compilerTemp5 = array(array(
		'value' => '0',
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'None' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	));
	if ($__templater->isTraversable($__vars['allAdmins'])) {
		foreach ($__vars['allAdmins'] AS $__vars['admin']) {
			$__compilerTemp5[] = array(
				'value' => $__vars['admin']['user_id'],
				'selected' => ($__vars['admin']['user_id'] == $__vars['autoForumManager']['admin_id']),
				'label' => $__templater->escape($__vars['admin']['username']),
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->form('
  <div class="block-container">
    <div class="block-body">
      <ul class="inputList">
		<li>
			' . $__compilerTemp2 . '
		  </li>
	</ul>
		
		' . $__templater->formSelectRow(array(
		'name' => 'admin_id',
		'required' => 'required',
	), $__compilerTemp5, array(
		'label' => 'Select Admin',
		'hint' => 'Required',
	)) . '

      ' . $__templater->formRow('
        ' . $__templater->formNumberBoxRow(array(
		'name' => 'total_days',
		'placeholder' => '123...!',
		'data-i' => '0',
		'value' => $__vars['autoForumManager']['last_login_days'],
	), array(
		'rowtype' => 'fullWidth',
	)) . '
      ', array(
		'rowtype' => 'input',
		'label' => 'Days',
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
		'action' => $__templater->func('link', array('forumMng/save', $__vars['autoForumManager'], ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-force-flash-message' => 'true',
	));
	return $__finalCompiled;
}
);