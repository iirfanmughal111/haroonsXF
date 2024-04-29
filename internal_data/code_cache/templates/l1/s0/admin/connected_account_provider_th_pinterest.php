<?php
// FROM HASH: 03544cdc6e86044f3915144e23725d68
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formTextBoxRow(array(
		'name' => 'options[app_id]',
		'value' => $__vars['options']['app_id'],
	), array(
		'label' => 'App ID',
		'hint' => 'Required',
		'explain' => 'The ID that is associated with your <a href="https://developers.pinterest.com/apps/" target="_blank">Pinterest application</a> for this domain.',
	)) . '

' . $__templater->formTextBoxRow(array(
		'name' => 'options[app_secret]',
		'value' => $__vars['options']['app_secret'],
	), array(
		'label' => 'App secret',
		'hint' => 'Required',
		'explain' => 'The secret for the Pinterest application you created for this domain.',
	));
	return $__finalCompiled;
}
);