<?php
// FROM HASH: 78f07cfbe8eab3360ebccd775d07a8a0
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<h3 class="block-formSectionHeader">' . 'Source database configuration' . '</h3>
';
	if (!$__vars['baseConfig']['db']['host']) {
		$__finalCompiled .= '
	' . $__templater->formTextBoxRow(array(
			'name' => 'config[db][host]',
			'value' => $__vars['db']['host'],
			'placeholder' => '$config[\'MasterServer\'][\'servername\']',
		), array(
			'label' => 'MySQL server',
		)) . '
	' . $__templater->formTextBoxRow(array(
			'name' => 'config[db][port]',
			'value' => $__vars['db']['port'],
			'placeholder' => '$config[\'MasterServer\'][\'port\']',
		), array(
			'label' => 'MySQL port',
		)) . '
	' . $__templater->formTextBoxRow(array(
			'name' => 'config[db][username]',
			'value' => $__vars['db']['username'],
			'placeholder' => '$config[\'MasterServer\'][\'username\']',
		), array(
			'label' => 'MySQL username',
		)) . '
	' . $__templater->formTextBoxRow(array(
			'name' => 'config[db][password]',
			'value' => $__vars['db']['password'],
			'autocomplete' => 'off',
			'placeholder' => '$config[\'MasterServer\'][\'password\']',
		), array(
			'label' => 'MySQL password',
		)) . '
	' . $__templater->formTextBoxRow(array(
			'name' => 'config[db][dbname]',
			'value' => $__vars['db']['dbname'],
			'placeholder' => '$config[\'Database\'][\'dbname\']',
		), array(
			'label' => 'MySQL database name',
		)) . '
	' . $__templater->formTextBoxRow(array(
			'name' => 'config[db][tablePrefix]',
			'value' => $__vars['db']['tablePrefix'],
			'placeholder' => '$config[\'Database\'][\'tableprefix\']',
		), array(
			'label' => 'MySQL table prefix',
		)) . '
	<hr class="formRowSep" />
	' . $__templater->formTextBoxRow(array(
			'name' => 'config[db][charset]',
			'value' => $__vars['db']['charset'],
			'placeholder' => '$config[\'Mysqli\'][\'charset\']',
		), array(
			'label' => 'Force character set',
			'explain' => 'If you specify a character set in the config for the system you are importing, you should specify the same character set here.',
		)) . '
	<hr class="formRowSep" />
	';
		if (!$__vars['baseConfig']['forum_import_log']) {
			$__finalCompiled .= '
		' . $__templater->formTextBoxRow(array(
				'name' => 'config[forum_import_log]',
			), array(
				'label' => 'Forum import log',
				'explain' => '
				' . 'You must provide the name of the import log that was generated when the forum was imported.' . '
			',
			)) . '
	';
		} else {
			$__finalCompiled .= '
		' . $__templater->formRow('
			' . $__templater->escape($__vars['fullConfig']['forum_import_log']) . '
		', array(
				'label' => 'Forum import log',
			)) . '
	';
		}
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	' . $__templater->formRow($__templater->escape($__vars['fullConfig']['db']['host']) . ':' . $__templater->escape($__vars['fullConfig']['db']['dbname']), array(
			'label' => 'MySQL server',
		)) . '
';
	}
	return $__finalCompiled;
}
);