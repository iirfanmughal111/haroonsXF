<?php
// FROM HASH: 31c45022d8d25d9149c783d9e2ce4bf2
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<hr class="formRowSep" />

' . $__templater->formNumberBoxRow(array(
		'name' => 'options[search]',
		'value' => $__vars['options']['search'],
		'min' => '0',
	), array(
		'label' => 'Enable search',
		'explain' => 'Enable search if there are more than x results.',
	)) . '

' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'options[avatar]',
		'selected' => $__vars['options']['avatar'],
		'label' => 'Display avatar',
		'_type' => 'option',
	)), array(
		'explain' => 'If enabled, the user avatar will be displayed before username.',
	)) . '

' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'options[grid]',
		'selected' => $__vars['options']['grid'],
		'label' => 'Enable grid mode',
		'_type' => 'option',
	)), array(
		'explain' => 'Allows you to display only the user avatar in a grid style.',
	));
	return $__finalCompiled;
}
);