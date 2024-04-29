<?php
// FROM HASH: f729e54f0025fa479442a33f1555a171
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formTextAreaRow(array(
		'name' => 'command_options[slap_objects]',
		'value' => $__vars['command']['command_options']['slap_objects'],
		'rows' => '10',
	), array(
		'label' => 'Slap objects',
		'explain' => 'By providing a list of objects here, the slap command will randomly select an object from the list to slap the user with. Place each object name on a new row.',
	));
	return $__finalCompiled;
}
);