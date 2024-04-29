<?php
// FROM HASH: 8103c24a61825c44ea49404914c221d1
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<hr class="formRowSep" />

' . $__templater->formNumberBoxRow(array(
		'name' => 'options[limit]',
		'value' => ($__vars['options']['limit'] ? $__vars['options']['limit'] : 5),
		'min' => '3',
	), array(
		'label' => 'Top chatters limit',
		'explain' => 'Specify the maximum number of users that should be shown in this widget.',
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