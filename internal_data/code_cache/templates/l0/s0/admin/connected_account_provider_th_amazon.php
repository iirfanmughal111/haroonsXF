<?php
// FROM HASH: 8cbdff219b2fbdeac9627c79e38c61eb
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formTextBoxRow(array(
		'name' => 'options[client_id]',
		'value' => $__vars['options']['client_id'],
	), array(
		'label' => 'Client ID',
		'hint' => 'Required',
		'explain' => 'The ID that is associated with your <a href="https://login.amazon.com/manageApps" target="_blank">Amazon application</a> for this domain.',
	)) . '

' . $__templater->formTextBoxRow(array(
		'name' => 'options[client_secret]',
		'value' => $__vars['options']['client_secret'],
	), array(
		'label' => 'Client secret',
		'hint' => 'Required',
		'explain' => 'The secret for the Amazon application you created for this domain.',
	));
	return $__finalCompiled;
}
);