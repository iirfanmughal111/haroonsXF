<?php
// FROM HASH: 93e7a542d5a5524f075e6b9a97630e0a
return array(
'macros' => array('table_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'autoForumManagerData' => $__vars['autoForumManager'],
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
  ' . $__templater->dataRow(array(
		'rowtype' => 'header',
	), array(array(
		'_type' => 'cell',
		'html' => ' ' . 'Admin' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'Forum' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'Days' . ' ',
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
  ';
	if ($__templater->isTraversable($__vars['autoForumManagerData'])) {
		foreach ($__vars['autoForumManagerData'] AS $__vars['value']) {
			$__finalCompiled .= '
    ' . $__templater->dataRow(array(
			), array(array(
				'_type' => 'cell',
				'html' => ' ' . $__templater->escape($__vars['value']['User']['username']) . ' ',
			),
			array(
				'_type' => 'cell',
				'html' => ' ' . $__templater->escape($__vars['value']['Node']['title']) . ' ',
			),
			array(
				'_type' => 'cell',
				'html' => ' ' . $__templater->escape($__vars['value']['last_login_days']) . ' ',
			),
			array(
				'href' => $__templater->func('link', array('forumMng/edit', $__vars['value'], ), false),
				'_type' => 'action',
				'html' => 'Edit',
			),
			array(
				'href' => $__templater->func('link', array('forumMng/delete', $__vars['value'], ), false),
				'overlay' => 'true',
				'_type' => 'delete',
				'html' => '',
			))) . '
  ';
		}
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Auto Forum Manage');
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
  ' . $__templater->button('Add Forum', array(
		'href' => $__templater->func('link', array('forumMng/add', ), false),
		'icon' => 'add',
	), '', array(
	)) . '
');
	$__finalCompiled .= '
';
	$__compilerTemp1 = '';
	if (!$__templater->test($__vars['autoForumManager'], 'empty', array())) {
		$__compilerTemp1 .= '
	<div class="block-body">
			' . $__templater->dataList('
					
				' . $__templater->callMacro(null, 'table_list', array(
			'autoForumManagerData' => $__vars['autoForumManager'],
		), $__vars) . '


				   ', array(
			'data-xf-init' => 'responsive-data-list',
		)) . '
				<div class="block-footer">
					  <span class="block-footer-counter"
						>' . $__templater->func('display_totals', array($__vars['totalReturn'], $__vars['total'], ), true) . '</span
					  >
				</div>
		    
		</div>
       ';
	} else {
		$__compilerTemp1 .= '
			<div class="block-body block-row">' . 'No items have been created yet.' . '</div>
		
       ';
	}
	$__finalCompiled .= $__templater->form('
  <div class="block-outer">
		' . $__templater->callMacro('filter_macros', 'quick_filter', array(
		'key' => 'forumMng',
		'class' => 'block-outer-opposite',
	), $__vars) . '
  </div>

  <div class="block-container">

  ' . $__compilerTemp1 . '
    
 </div>


   ' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'forumMng',
		'wrapperclass' => 'block',
		'perPage' => $__vars['perPage'],
	))) . '
', array(
		'action' => $__templater->func('link', array($__vars['prefix'] . '/toggle', ), false),
		'class' => 'block',
		'ajax' => 'true',
	)) . '
';
	return $__finalCompiled;
}
);