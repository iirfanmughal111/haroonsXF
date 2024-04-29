<?php
// FROM HASH: 000648f50b2267f238dbad5c9b067c22
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formTextBoxRow(array(
		'name' => 'options[key]',
		'value' => $__vars['options']['key'],
	), array(
		'label' => 'API key',
		'hint' => 'Required',
		'explain' => 'thuserimprovements_steam_api_id_explain',
	));
	return $__finalCompiled;
}
);