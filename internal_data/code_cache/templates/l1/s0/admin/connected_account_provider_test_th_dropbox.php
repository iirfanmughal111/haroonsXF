<?php
// FROM HASH: 96334778a7a84a5522220cb00127c84e
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

	' . $__templater->formRow($__templater->escape($__vars['provider']['options']['app_key']), array(
			'label' => 'App key',
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