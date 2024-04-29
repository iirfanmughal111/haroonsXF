<?php
// FROM HASH: efdbd7b95cd45571aa4eb1953fb5c4d6
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
		'explain' => 'The ID that is associated with your <a href="https://www.instagram.com/developer" target="_blank">Instagram application</a> for this domain.',
	)) . '

' . $__templater->formTextBoxRow(array(
		'name' => 'options[client_secret]',
		'value' => $__vars['options']['client_secret'],
	), array(
		'label' => 'Client secret',
		'hint' => 'Required',
		'explain' => 'The secret for the Instagram application you created for this domain.',
	));
	return $__finalCompiled;
}
);