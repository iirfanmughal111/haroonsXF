<?php
// FROM HASH: 942d90d061ac05a1840575670d98b768
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formTextBoxRow(array(
		'name' => 'options[api_key]',
		'value' => $__vars['options']['api_key'],
	), array(
		'label' => 'API key',
		'hint' => 'Required',
		'explain' => 'The ID that is associated with your <a href="https://dev.battle.net/apps/mykeys" target="_blank">Battle.net application</a> for this domain.',
	)) . '

' . $__templater->formTextBoxRow(array(
		'name' => 'options[api_secret]',
		'value' => $__vars['options']['api_secret'],
	), array(
		'label' => 'API secret',
		'hint' => 'Required',
		'explain' => 'The secret for the Battle.net application you created for this domain.',
	));
	return $__finalCompiled;
}
);