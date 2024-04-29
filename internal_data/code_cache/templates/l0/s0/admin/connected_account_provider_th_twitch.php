<?php
// FROM HASH: 553f0b0c05569740e92dbec64e80416c
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
		'explain' => 'The ID that is associated with your <a href="https://dev.twitch.tv/dashboard/apps" target="_blank">Twitch application</a> for this domain.',
	)) . '

' . $__templater->formTextBoxRow(array(
		'name' => 'options[client_secret]',
		'value' => $__vars['options']['client_secret'],
	), array(
		'label' => 'Client secret',
		'hint' => 'Required',
		'explain' => 'The secret for the Twitch application you created for this domain.',
	));
	return $__finalCompiled;
}
);