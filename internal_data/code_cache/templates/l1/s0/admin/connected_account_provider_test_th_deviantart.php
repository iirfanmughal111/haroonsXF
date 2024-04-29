<?php
// FROM HASH: c1c09aaadc9d2fe9f435c19a8a1b3b67
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

	' . $__templater->formRow(($__templater->escape($__vars['providerData']['email']) ?: 'N/A'), array(
			'label' => 'Email',
		)) . '

	';
		$__compilerTemp1 = '';
		if ($__vars['providerData']['avatar_url']) {
			$__compilerTemp1 .= '
			<img src="' . $__templater->escape($__vars['providerData']['avatar_url']) . '" width="48" />
		';
		} else {
			$__compilerTemp1 .= '
			N/A
		';
		}
		$__finalCompiled .= $__templater->formRow('
		' . $__compilerTemp1 . '
	', array(
			'label' => 'Picture',
		)) . '
';
	}
	return $__finalCompiled;
}
);