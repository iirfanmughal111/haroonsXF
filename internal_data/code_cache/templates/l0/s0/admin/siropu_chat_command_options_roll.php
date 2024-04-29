<?php
// FROM HASH: 50f79768cfda8ccbcbc3d1322bbf815f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formNumberBoxRow(array(
		'name' => 'command_options[default_dice_count]',
		'value' => ($__vars['command']['command_options']['default_dice_count'] ?: 1),
		'min' => '1',
	), array(
		'label' => 'Default dice count',
		'explain' => 'The number of dices to roll by default.',
	)) . '

' . $__templater->formNumberBoxRow(array(
		'name' => 'command_options[default_dice_sides]',
		'value' => ($__vars['command']['command_options']['default_dice_sides'] ?: 6),
		'min' => '2',
	), array(
		'label' => 'Default dice sides',
		'explain' => 'The number of sides a dice will have by default.',
	)) . '

' . $__templater->formNumberBoxRow(array(
		'name' => 'command_options[max_dice_count]',
		'value' => ($__vars['command']['command_options']['max_dice_count'] ?: 1),
		'min' => '1',
	), array(
		'label' => 'Maximum dice count',
		'explain' => 'The maximum number of dices a user can roll.',
	)) . '

' . $__templater->formNumberBoxRow(array(
		'name' => 'command_options[max_dice_sides]',
		'value' => ($__vars['command']['command_options']['max_dice_sides'] ?: 6),
		'min' => '2',
	), array(
		'label' => 'Maximum dice sides',
		'explain' => 'The maximum dice sides a user can roll.',
	)) . '

' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'command_options[dice_sum]',
		'value' => '1',
		'label' => 'Sum the result of multiple dices',
		'checked' => ($__vars['command']['command_options']['dice_sum'] == 1),
		'_type' => 'option',
	)), array(
	));
	return $__finalCompiled;
}
);