<?php
// FROM HASH: e384b8ae6a09dd56937e1e1ca8ddb2e0
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = '';
	if (!$__templater->test($__vars['user'], 'empty', array())) {
		$__compilerTemp1 .= ': ' . $__templater->escape($__vars['user']['username']);
	}
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Sanction user' . $__compilerTemp1);
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formSelectRow(array(
		'name' => 'sanction_type',
		'value' => 'kick',
		'class' => 'input--autoSize',
	), array(array(
		'value' => 'mute',
		'label' => 'Mute',
		'_type' => 'option',
	),
	array(
		'value' => 'kick',
		'label' => 'Kick',
		'_type' => 'option',
	)), array(
		'label' => 'Sanction type',
	)) . '
			' . $__templater->formNumberBoxRow(array(
		'name' => 'length',
		'min' => '1',
		'max' => '24',
		'units' => 'Hours',
	), array(
		'label' => 'Sanction length',
	)) . '
			' . $__templater->formTextBoxRow(array(
		'name' => 'reason',
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
		'action' => $__templater->func('link', array('chat/room/sanction', array('room_id' => $__vars['room']['room_id'], 'user_id' => $__vars['user']['user_id'], ), ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);