<?php
// FROM HASH: ec695e848cacfa511225d899ea9543fe
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formTextBoxRow(array(
		'name' => 'options[consumer_key]',
		'value' => $__vars['options']['consumer_key'],
	), array(
		'label' => 'Consumer key',
		'hint' => 'Required',
		'explain' => 'The ID that is associated with your Tumblr application for this domain.',
	)) . '

' . $__templater->formTextBoxRow(array(
		'name' => 'options[secret_key]',
		'value' => $__vars['options']['secret_key'],
	), array(
		'label' => 'Secret key',
		'hint' => 'Required',
		'explain' => 'The secret for the Tumblr application you created for this domain.',
	));
	return $__finalCompiled;
}
);