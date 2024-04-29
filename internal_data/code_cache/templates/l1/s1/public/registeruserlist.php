<?php
// FROM HASH: 1d170fdb452d37f83b9bee43dc5e1ca1
return array(
'macros' => array('userlist' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'users' => '!',
		'tour' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	
	
	';
	if (!$__templater->test($__vars['users'], 'empty', array())) {
		$__finalCompiled .= '
			<div class="block-body">
				';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['users'])) {
			foreach ($__vars['users'] AS $__vars['user']) {
				$__compilerTemp1 .= '
	' . $__templater->dataRow(array(
				), array(array(
					'_type' => 'cell',
					'html' => '
				' . $__templater->func('username_link', array($__vars['user']['User'], true, array(
					'itemprop' => 'name',
				))) . '
				
				',
				),
				array(
					'_type' => 'cell',
					'html' => $__templater->func('date', array($__vars['user']['current_time'], 'Y-m-d', ), true),
				))) . '
	';
			}
		}
		$__finalCompiled .= $__templater->dataList('
					' . $__templater->dataRow(array(
			'rowtype' => 'header',
		), array(array(
			'_type' => 'cell',
			'html' => 'User',
		),
		array(
			'_type' => 'cell',
			'html' => 'Date',
		))) . '
								
	' . $__compilerTemp1 . '
				', array(
			'data-xf-init' => 'responsive-data-list',
		)) . '
		</div>

	';
	}
	$__finalCompiled .= '
	
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';

	return $__finalCompiled;
}
);