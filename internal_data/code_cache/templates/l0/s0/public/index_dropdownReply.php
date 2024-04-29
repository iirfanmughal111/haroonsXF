<?php
// FROM HASH: 2bf9a6f3d18c69e9410d7d4cfa8c915b
return array(
'macros' => array('usergroup_table_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'dropdownReplys' => $__vars['dropdownReplys'],
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
  ' . $__templater->dataRow(array(
		'rowtype' => 'header',
	), array(array(
		'_type' => 'cell',
		'html' => ' ' . 'Thread Title' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'Status' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'Options' . ' ',
	),
	array(
		'class' => 'dataList-cell--min',
		'_type' => 'cell',
		'html' => '',
	),
	array(
		'class' => 'dataList-cell--min',
		'_type' => 'cell',
		'html' => '',
	))) . '

  ' . $__templater->dataRow(array(
	), array(array(
		'_type' => 'cell',
		'html' => ' ' . $__templater->escape($__vars['dropdownReplys']['title']) . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => '
      ' . (($__vars['dropdownReplys']['is_dropdown_active'] == 0) ? 'Inactive' : 'Active') . '
    ',
	),
	array(
		'_type' => 'cell',
		'html' => '
      ' . $__templater->func('snippet', array($__vars['dropdownReplys']['dropdwon_options']['0'], 10, array('stripBbCode' => true, ), ), true) . '
    ',
	),
	array(
		'href' => $__templater->func('link', array('dropdownreply/edit', $__vars['dropdownReplys'], ), false),
		'_type' => 'action',
		'html' => 'Edit',
	),
	array(
		'href' => $__templater->func('link', array('dropdownreply/delete', $__vars['dropdownReplys'], ), false),
		'overlay' => 'true',
		'_type' => 'delete',
		'html' => '',
	))) . '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Dropdown Reply');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['dropdownReplys'], 'getBreadcrumbs', array(false, )));
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
  ' . $__templater->button('fs_dropdown_reply', array(
		'href' => $__templater->func('link', array('dropdownreply/add', $__vars['dropdownReplys'], ), false),
		'icon' => 'add',
	), '', array(
	)) . '
');
	$__finalCompiled .= '
';
	$__compilerTemp1 = '';
	if ($__vars['dropdownReplys']['dropdwon_options'] != null) {
		$__compilerTemp1 .= '
      <div class="block-body">
        ' . $__templater->dataList('
          ' . $__templater->callMacro(null, 'usergroup_table_list', array(
			'usergroupData' => $__vars['dropdownReplys'],
		), $__vars) . '
        ', array(
			'data-xf-init' => 'responsive-data-list',
		)) . '
      </div>
      ';
	} else {
		$__compilerTemp1 .= '
      <div class="block-body block-row">
        ' . 'No items have been created yet.' . '
      </div>
    ';
	}
	$__finalCompiled .= $__templater->form('
  <div class="block-container">
    ' . $__compilerTemp1 . '
  </div>
', array(
		'action' => $__templater->func('link', array($__vars['prefix'] . '/toggle', ), false),
		'class' => 'block',
		'ajax' => 'true',
	)) . '
';
	return $__finalCompiled;
}
);