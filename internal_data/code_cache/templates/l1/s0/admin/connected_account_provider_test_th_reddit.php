<?php
// FROM HASH: 064be54ef1369e18917da943ddc8991e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__vars['providerData']) {
		$__finalCompiled .= '
	' . $__templater->formInfoRow('
		' . 'This will test the ' . $__templater->escape($__vars['provider']['title']) . ' connected account provider. You must have a ' . $__templater->escape($__vars['provider']['title']) . ' account to perform this test.' . '
	', array(
		)) . '

	' . $__templater->formRow($__templater->escape($__vars['provider']['options']['client_id']), array(
			'label' => 'Client ID',
		)) . '
';
	} else {
		$__finalCompiled .= '
	' . $__templater->formInfoRow('<strong>' . 'Test passed!' . '</strong>', array(
			'rowtype' => 'confirm',
		)) . '

	' . $__templater->formRow(($__templater->escape($__vars['providerData']['username']) ?: 'N/A'), array(
			'label' => 'Name',
		)) . '
';
	}
	return $__finalCompiled;
}
);