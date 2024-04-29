<?php
// FROM HASH: 31676c14fd9ee95ffa87fd04d2cda325
return array(
'macros' => array('usergroup_table_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'usergroupData' => '',
		'urlType' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
  ';
	$__compilerTemp1 = array(array(
		'_type' => 'cell',
		'html' => ' ' . 'Current Usergroup' . ' ',
	)
,array(
		'_type' => 'cell',
		'html' => ' ' . 'Upgrade Usergroup' . ' ',
	));
	if ($__vars['urlType'] == 'upgradeGroup') {
		$__compilerTemp1[] = array(
			'_type' => 'cell',
			'html' => ' ' . 'Messages' . ' ',
		);
	} else {
		$__compilerTemp1[] = array(
			'_type' => 'cell',
			'html' => ' ' . 'Last login' . ' ',
		);
	}
	$__compilerTemp1[] = array(
		'class' => 'dataList-cell--min',
		'_type' => 'cell',
		'html' => '',
	);
	$__compilerTemp1[] = array(
		'class' => 'dataList-cell--min',
		'_type' => 'cell',
		'html' => '',
	);
	$__finalCompiled .= $__templater->dataRow(array(
		'rowtype' => 'header',
	), $__compilerTemp1) . '
  ';
	if ($__templater->isTraversable($__vars['usergroupData'])) {
		foreach ($__vars['usergroupData'] AS $__vars['value']) {
			$__finalCompiled .= '
    ';
			$__compilerTemp2 = array(array(
				'_type' => 'cell',
				'html' => ' ' . $__templater->escape($__vars['value']['UserGroup']['title']) . ' ',
			)
,array(
				'_type' => 'cell',
				'html' => ' ' . $__templater->escape($__vars['value']['UserGroups']['title']) . ' ',
			));
			if ($__vars['urlType'] == 'upgradeGroup') {
				$__compilerTemp2[] = array(
					'_type' => 'cell',
					'html' => ' ' . $__templater->escape($__vars['value']['message_count']) . ' ',
				);
			} else {
				$__compilerTemp2[] = array(
					'_type' => 'cell',
					'html' => ' ' . $__templater->escape($__vars['value']['last_login']) . ' ',
				);
			}
			$__compilerTemp2[] = array(
				'href' => $__templater->func('link', array(('' . $__vars['urlType']) . '/edit', $__vars['value'], ), false),
				'_type' => 'action',
				'html' => 'Edit',
			);
			$__compilerTemp2[] = array(
				'href' => $__templater->func('link', array(('' . $__vars['urlType']) . '/delete', $__vars['value'], ), false),
				'overlay' => 'true',
				'_type' => 'delete',
				'html' => '',
			);
			$__finalCompiled .= $__templater->dataRow(array(
			), $__compilerTemp2) . '
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
	if ($__vars['urlType'] == 'upgradeGroup') {
		$__finalCompiled .= '
';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Upgrade UserGroup');
		$__finalCompiled .= '
	';
	} else {
		$__finalCompiled .= '
  ';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Change Usergroup');
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['urlType'] == 'upgradeGroup') {
		$__compilerTemp1 .= '
	' . 'Add Usergroup' . '
	';
	} else {
		$__compilerTemp1 .= '
  ' . 'Change usergroup' . '
	';
	}
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
  ' . $__templater->button('
	  ' . $__compilerTemp1 . '
	  ', array(
		'href' => $__templater->func('link', array(('' . $__vars['urlType']) . '/add', ), false),
		'icon' => 'add',
	), '', array(
	)) . '
');
	$__finalCompiled .= '
';
	$__compilerTemp2 = '';
	if (!$__templater->test($__vars['upgradeUserGroup'], 'empty', array())) {
		$__compilerTemp2 .= '
	<div class="block-body">
			' . $__templater->dataList('
					
				' . $__templater->callMacro(null, 'usergroup_table_list', array(
			'usergroupData' => $__vars['upgradeUserGroup'],
			'urlType' => $__vars['urlType'],
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
		$__compilerTemp2 .= '
			<div class="block-body block-row">' . 'No items have been created yet.' . '</div>
		
       ';
	}
	$__finalCompiled .= $__templater->form('
  <div class="block-outer">
		' . $__templater->callMacro('filter_macros', 'quick_filter', array(
		'key' => $__vars['urlType'],
		'class' => 'block-outer-opposite',
	), $__vars) . '
  </div>

  <div class="block-container">

  ' . $__compilerTemp2 . '
    
 </div>


   ' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => $__vars['urlType'],
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