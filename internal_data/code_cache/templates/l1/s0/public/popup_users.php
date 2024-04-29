<?php
// FROM HASH: c9619d1b27ae44cab4853fabcdd052e3
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
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
					'html' => $__templater->escape($__vars['user']['User']['username']),
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
	return $__finalCompiled;
}
);