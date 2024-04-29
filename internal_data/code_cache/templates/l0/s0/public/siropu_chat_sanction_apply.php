<?php
// FROM HASH: e5cf26a42f2c078acdd46b2c36d995b8
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['sanction'], 'isUpdate', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit sanction');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__compilerTemp1 = '';
		if (!$__templater->test($__vars['user'], 'empty', array())) {
			$__compilerTemp1 .= ': ' . $__templater->escape($__vars['user']['username']);
		}
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Sanction user' . $__compilerTemp1);
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__compilerTemp2 = '';
	if (!$__templater->test($__vars['user'], 'empty', array())) {
		$__compilerTemp2 .= '
				' . $__templater->formHiddenVal('user_id', $__vars['user']['user_id'], array(
		)) . '
			';
	} else if ($__templater->method($__vars['sanction'], 'isInsert', array())) {
		$__compilerTemp2 .= '
				' . $__templater->formTextBoxRow(array(
			'name' => 'username',
			'value' => $__vars['user']['username'],
			'data-xf-init' => 'auto-complete',
			'required' => 'required',
		), array(
			'label' => 'User',
		)) . '
			';
	}
	$__compilerTemp3 = '';
	if ($__templater->method($__vars['sanction'], 'isInsert', array())) {
		$__compilerTemp3 .= '
				';
		$__compilerTemp4 = array();
		if ($__templater->isTraversable($__vars['rooms'])) {
			foreach ($__vars['rooms'] AS $__vars['room']) {
				$__compilerTemp4[] = array(
					'value' => $__vars['room']['room_id'],
					'label' => $__templater->escape($__vars['room']['room_name']),
					'_type' => 'option',
				);
			}
		}
		$__compilerTemp3 .= $__templater->formRadioRow(array(
			'name' => 'method',
			'value' => ($__vars['roomId'] ? 'room' : 'chat'),
		), array(array(
			'value' => 'chat',
			'label' => 'All rooms',
			'_type' => 'option',
		),
		array(
			'value' => 'room',
			'label' => 'Selected rooms',
			'_dependent' => array('
							' . $__templater->formSelect(array(
			'name' => 'room_id',
			'value' => $__vars['roomId'],
			'multiple' => 'true',
		), $__compilerTemp4) . '
						'),
			'_type' => 'option',
		)), array(
			'label' => 'Apply sanction for',
		)) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__compilerTemp2 . '
			' . $__templater->formSelectRow(array(
		'name' => 'sanction_type',
		'value' => ($__vars['sanction']['sanction_type'] ?: 'ban'),
		'class' => 'input--autoSize',
	), array(array(
		'value' => 'ban',
		'label' => 'Ban',
		'_type' => 'option',
	),
	array(
		'value' => 'kick',
		'label' => 'Kick',
		'_type' => 'option',
	),
	array(
		'value' => 'mute',
		'label' => 'Mute',
		'_type' => 'option',
	)), array(
		'label' => 'Sanction type',
	)) . '
			' . $__compilerTemp3 . '
			' . $__templater->formRadioRow(array(
		'name' => 'length_type',
		'value' => (($__vars['sanction']['sanction_end'] == 0) ? 'perm' : 'temp'),
	), array(array(
		'value' => 'perm',
		'label' => 'Permanent',
		'_type' => 'option',
	),
	array(
		'value' => 'temp',
		'label' => 'Temporary',
		'_dependent' => array('
							<div class="inputGroup" style="margin-bottom: 5px;">
								' . $__templater->formNumberBox(array(
		'name' => 'length_value',
		'min' => '1',
	)) . '
								' . $__templater->formSelect(array(
		'name' => 'length_option',
		'class' => 'input--autoSize',
		'style' => 'margin-left: 5px;',
	), array(array(
		'value' => 'hours',
		'label' => 'Hours',
		'_type' => 'option',
	),
	array(
		'value' => 'days',
		'label' => 'Days',
		'_type' => 'option',
	),
	array(
		'value' => 'weeks',
		'label' => 'Weeks',
		'_type' => 'option',
	),
	array(
		'value' => 'months',
		'label' => 'Months',
		'_type' => 'option',
	),
	array(
		'value' => 'years',
		'label' => 'Years',
		'_type' => 'option',
	))) . '
							</div>
							' . $__templater->formDateInput(array(
		'name' => 'length_date',
		'placeholder' => 'Until' . $__vars['xf']['language']['ellipsis'],
	)) . '
					'),
		'_type' => 'option',
	)), array(
		'label' => 'Sanction length',
	)) . '
			' . $__templater->formTextBoxRow(array(
		'name' => 'reason',
		'value' => $__vars['sanction']['sanction_reason'],
	), array(
		'label' => 'Sanction reason',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'submit' => 'Apply sanction',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('chat/sanction/apply', $__vars['sanction'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);