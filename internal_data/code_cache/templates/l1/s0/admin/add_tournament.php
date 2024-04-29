<?php
// FROM HASH: 8d778a7bc2965893d87222a1e168e65d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Tournament');
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Add Tournament', array(
		'href' => $__templater->func('link', array('tourn/newentry', ), false),
		'icon' => 'add',
	), '', array(
	)) . '
');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if (!$__templater->test($__vars['records'], 'empty', array())) {
		$__compilerTemp1 .= '
			<div class="block-body">
				';
		$__compilerTemp2 = '';
		if ($__templater->isTraversable($__vars['records'])) {
			foreach ($__vars['records'] AS $__vars['record']) {
				$__compilerTemp2 .= '
						' . $__templater->dataRow(array(
				), array(array(
					'_type' => 'cell',
					'html' => $__templater->escape($__vars['record']['tourn_title']),
				),
				array(
					'_type' => 'cell',
					'html' => $__templater->escape($__vars['record']['tourn_domain']),
				),
				array(
					'_type' => 'cell',
					'html' => $__templater->escape($__templater->method($__vars['record'], 'getStartDate', array())),
				),
				array(
					'_type' => 'cell',
					'html' => $__templater->escape($__templater->method($__vars['record'], 'getStartTime', array())),
				),
				array(
					'_type' => 'cell',
					'html' => $__templater->escape($__templater->method($__vars['record'], 'getEndDate', array())),
				),
				array(
					'_type' => 'cell',
					'html' => $__templater->escape($__templater->method($__vars['record'], 'getEndTime', array())),
				),
				array(
					'href' => $__templater->func('link', array('tourn/edit', $__vars['record'], ), false),
					'_type' => 'action',
					'html' => 'Edit',
				),
				array(
					'href' => $__templater->func('link', array('tourn/delete', $__vars['record'], ), false),
					'_type' => 'delete',
					'html' => '',
				))) . '
					';
			}
		}
		$__compilerTemp1 .= $__templater->dataList('
					' . $__templater->dataRow(array(
			'rowtype' => 'header',
		), array(array(
			'_type' => 'cell',
			'html' => 'Tournament Title',
		),
		array(
			'_type' => 'cell',
			'html' => 'Domain',
		),
		array(
			'_type' => 'cell',
			'html' => 'Start date',
		),
		array(
			'_type' => 'cell',
			'html' => 'Start Time',
		),
		array(
			'_type' => 'cell',
			'html' => 'End date',
		),
		array(
			'_type' => 'cell',
			'html' => 'End Time',
		),
		array(
			'class' => 'dataList-cell--min',
			'_type' => 'cell',
			'html' => '&nbsp;',
		),
		array(
			'class' => 'dataList-cell--min',
			'_type' => 'cell',
			'html' => '&nbsp;',
		))) . '
					' . $__compilerTemp2 . '
				', array(
			'data-xf-init' => 'responsive-data-list',
		)) . '
			</div>
			<div class="block-footer">
				<span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['records'], $__vars['total'], ), true) . '</span>
			</div>
		';
	} else {
		$__compilerTemp1 .= '
			<div class="block-body block-row">' . 'No results found.' . '</div>
		';
	}
	$__finalCompiled .= $__templater->form('
	
	<div class="block-outer">
		' . $__templater->callMacro('tournament_search_macro', 'login_filter', array(
		'key' => '',
		'class' => 'block-outer-opposite',
	), $__vars) . '
	</div>
	
	<div class="block-container">
		' . $__compilerTemp1 . '
	</div>

	' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'tourn',
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer block-outer--after',
		'perPage' => $__vars['perPage'],
	))) . '
', array(
		'action' => $__templater->func('link', array('entries/toggle', $__vars['record'], ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);