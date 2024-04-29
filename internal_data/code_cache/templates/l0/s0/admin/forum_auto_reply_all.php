<?php
// FROM HASH: c68d257f296ee4be253ee9aa4fed50af
return array(
'macros' => array('message_table_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'Messages' => $__vars['Messages'],
		'nodeTree' => $__vars['nodeTree'],
		'userGroups' => $__vars['userGroups'],
		'prefixGroups' => $__vars['prefixGroups'],
		'prefixesGrouped' => $__vars['prefixesGrouped'],
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
  ' . $__templater->dataRow(array(
		'rowtype' => 'header',
	), array(array(
		'_type' => 'cell',
		'html' => ' ' . 'Forum' . ' ',
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
	if ($__templater->isTraversable($__vars['Messages'])) {
		foreach ($__vars['Messages'] AS $__vars['msg']) {
			$__finalCompiled .= '
    ';
			$__compilerTemp1 = array();
			$__compilerTemp2 = $__templater->method($__vars['nodeTree'], 'getFlattened', array(0, ));
			if ($__templater->isTraversable($__compilerTemp2)) {
				foreach ($__compilerTemp2 AS $__vars['treeEntry']) {
					if ($__vars['treeEntry']['record']['node_id'] == $__vars['msg']['node_id']) {
						$__compilerTemp1[] = array(
							'_type' => 'cell',
							'html' => '
            ' . $__templater->filter($__templater->func('repeat', array('', $__vars['treeEntry']['depth'], ), false), array(array('raw', array()),), true) . ' ' . $__templater->escape($__vars['treeEntry']['record']['title']) . '
          ',
						);
					}
				}
			}
			$__compilerTemp1[] = array(
				'href' => $__templater->func('link', array('forumAutoReply/edit', $__vars['msg'], ), false),
				'_type' => 'action',
				'html' => 'Edit',
			);
			$__compilerTemp1[] = array(
				'href' => $__templater->func('link', array('forumAutoReply/delete-all', $__vars['msg'], ), false),
				'overlay' => 'true',
				'_type' => 'delete',
				'html' => '',
			);
			$__finalCompiled .= $__templater->dataRow(array(
			), $__compilerTemp1) . '
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
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Forum Auto Reply');
	$__finalCompiled .= '


';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
  ' . $__templater->button('Add Message', array(
		'href' => $__templater->func('link', array('forumAutoReply/add', ), false),
		'icon' => 'add',
	), '', array(
	)) . '
');
	$__finalCompiled .= '
';
	$__compilerTemp1 = '';
	if (!$__templater->test($__vars['Messages'], 'empty', array())) {
		$__compilerTemp1 .= '
	<div class="block-body">
			' . $__templater->dataList('
					
				' . $__templater->callMacro(null, 'message_table_list', array(
			'Messages' => $__vars['Messages'],
			'nodeTree' => $__vars['nodeTree'],
			'userGroups' => $__vars['userGroups'],
			'prefixGroups' => $__vars['prefixGroups'],
			'prefixesGrouped' => $__vars['prefixesGrouped'],
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
		'key' => 'forumAutoReply',
		'class' => 'block-outer-opposite',
	), $__vars) . '
  </div>

  <div class="block-container">

  ' . $__compilerTemp1 . '
    
 </div>


   ' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'forumAutoReply',
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