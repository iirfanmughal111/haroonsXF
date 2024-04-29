<?php
// FROM HASH: 699876cb79c4f8681614c7c5f7a84611
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formTextBoxRow(array(
		'name' => 'options[app_key]',
		'value' => $__vars['options']['app_key'],
	), array(
		'label' => 'App key',
		'hint' => 'Required',
		'explain' => 'The ID that is associated with your <a href="https://dropbox.com/developers/apps" target="_blank">Dropbox application</a> for this domain.',
	)) . '

' . $__templater->formTextBoxRow(array(
		'name' => 'options[app_secret]',
		'value' => $__vars['options']['app_secret'],
	), array(
		'label' => 'App secret',
		'hint' => 'Required',
		'explain' => 'The secret for the Dropbox application you created for this domain.',
	));
	return $__finalCompiled;
}
);