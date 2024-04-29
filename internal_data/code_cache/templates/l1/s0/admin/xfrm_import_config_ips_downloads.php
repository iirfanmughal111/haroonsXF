<?php
// FROM HASH: e9984fd1baa2120ae84e89ede3e6bb69
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
			'placeholder' => '$INFO[\'sql_host\']',
		), array(
			'label' => 'MySQL server',
		)) . '
	' . $__templater->formTextBoxRow(array(
			'name' => 'config[db][port]',
			'value' => $__vars['db']['port'],
			'placeholder' => '$INFO[\'sql_port\']',
		), array(
			'label' => 'MySQL port',
		)) . '
	' . $__templater->formTextBoxRow(array(
			'name' => 'config[db][username]',
			'value' => $__vars['db']['username'],
			'placeholder' => '$INFO[\'sql_user\']',
		), array(
			'label' => 'MySQL username',
		)) . '
	' . $__templater->formTextBoxRow(array(
			'name' => 'config[db][password]',
			'value' => $__vars['db']['password'],
			'autocomplete' => 'off',
			'placeholder' => '$INFO[\'sql_pass\']',
		), array(
			'label' => 'MySQL password',
		)) . '
	' . $__templater->formTextBoxRow(array(
			'name' => 'config[db][dbname]',
			'value' => $__vars['db']['dbname'],
			'placeholder' => '$INFO[\'sql_database\']',
		), array(
			'label' => 'MySQL database name',
		)) . '
	' . $__templater->formTextBoxRow(array(
			'name' => 'config[db][tablePrefix]',
			'value' => $__vars['db']['tablePrefix'],
			'placeholder' => '$INFO[\'sql_tbl_prefix\']',
		), array(
			'label' => 'MySQL table prefix',
		)) . '
';
	} else {
		$__finalCompiled .= '
	' . $__templater->formRow($__templater->escape($__vars['fullConfig']['db']['host']) . ':' . $__templater->escape($__vars['fullConfig']['db']['dbname']), array(
			'label' => 'MySQL server',
		)) . '
';
	}
	$__finalCompiled .= '

';
	if (!$__vars['baseConfig']['ips_path']) {
		$__finalCompiled .= '
	<hr class="formRowSep" />

	' . $__templater->formTextBoxRow(array(
			'name' => 'config[ips_path]',
		), array(
			'label' => 'Path to IPS Forums',
			'explain' => '
			~~Provide the path to your IPS Forums directory, or the directory which contains your forum\'s <code>uploads</code> directory.~~
		',
		)) . '
';
	} else {
		$__finalCompiled .= '
	' . $__templater->formRow($__templater->escape($__vars['fullConfig']['ips_path']), array(
			'label' => 'Path to IPS Forums',
		)) . '
';
	}
	$__finalCompiled .= '

';
	if (!$__vars['baseConfig']['forum_import_log']) {
		$__finalCompiled .= '
	<hr class="formRowSep" />
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
	' . $__templater->formRow($__templater->escape($__vars['fullConfig']['forum_import_log']), array(
			'label' => 'Forum import log',
		)) . '
';
	}
	return $__finalCompiled;
}
);