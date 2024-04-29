<?php
// FROM HASH: fc61b8d719fa009c35c3f9022a1b0914
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
		'explain' => 'The ID that is associated with your <a href="https://ssl.reddit.com/prefs/apps" target="_blank">Reddit application</a> for this domain.',
	)) . '

' . $__templater->formTextBoxRow(array(
		'name' => 'options[app_secret]',
		'value' => $__vars['options']['app_secret'],
	), array(
		'label' => 'App secret',
		'hint' => 'Required',
		'explain' => 'The secret for the Reddit application you created for this domain.',
	));
	return $__finalCompiled;
}
);